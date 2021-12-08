<?php

namespace App\Commands;

use App\Objects\Guesser\GuesserFiles;
use App\Objects\ReportExecution;
use App\Objects\Workflow\StepPhpObject;
use App\Objects\Workflow\YamlObject;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Arr;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Yaml\Yaml;

class GuessWorkflow extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'yaml:guess
    {projectpath? : Project Path}
    {--mysql : Setup and Run MySql service in the Workflow}
    {--show : Show the workflow}
    {--save : Save the workflow in Yaml file}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Guess Workflow configuration based on some files of the project';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $guesserFiles = new GuesserFiles();
        $projectDir = $this->argument("projectpath");
        $mysqlOption = $this->option("mysql");
        $showOption = $this->option("show");
        $saveOption = $this->option("save");

        $report = new ReportExecution();

        $yaml = YamlObject::make();
        $yaml->setName("Workflow " . date("%Y-%m-%d"));
        $yaml->setOnPushBranches(["main"]);
        $yaml->setMatrix("os", ['ubuntu-latest']);
        $yaml->setRunsOnMatrix();


        if ($mysqlOption) {
            $yaml->addMysqlService();
            $report->addValueInfo("Mysql Service", "Active");
        }
        $yaml->addStepFromTemplate("checkout");

        $guesserFiles->pathFiles($projectDir);
        if ($guesserFiles->composerExists()) {
            $report->addValueInfo("Composer File", "Loaded");
            $composer = json_decode(file_get_contents($guesserFiles->getComposerPath()), true);
            $yaml->setName(Arr::get($composer, 'name', "Workflow"));
            $report->addValue("Project Name", $yaml->getName());

            // MATRIX
            $phpversion = Arr::get($composer, 'require.php', "8.0");
            $stepPhp = $guesserFiles->detectPhpVersion($phpversion);
            $yaml->setMatrix("php-versions", $stepPhp);
            $report->addValue("PHP versions", $stepPhp);
            // STEP VERSION
            $yaml->addStepFromTemplates([
                "use-php", "php-version", "install-dependencies-composer"
            ]);
            $report->addValueInfo("Install dependencies", 'Composer/PHP');
            $devPackages = Arr::get($composer, 'require-dev');
            // Code Sniffer Tool
            $codeQualityList = [
                "squizlabs/php_codesniffer" => "execute-phpcs",
                "phpstan/phpstan" => "execute-phpstan",
                "phpunit/phpunit" => "execute-phpunit",
                "pestphp/pest" => "execute-pest"

            ];
            foreach ($codeQualityList as $cqPackage => $cqKey) {
                if (key_exists($cqPackage, $devPackages)) {
                    $yaml->addStepFromTemplate($cqKey);
                    $report->addValueInfo("Code Quality", $cqPackage);
                }
            }
        } else {
            $report[] = ["Composer File" , "not Found"];
        }
        if ($guesserFiles->packageExists()) {
            $versionFromNvmrc = $guesserFiles->readNvmrc($guesserFiles
                ->getNvmrcPath());
            if ($versionFromNvmrc === "") {
                $versionFromNvmrc = "16.x";
            }
            $yaml->setMatrix("node-versions", [$versionFromNvmrc]);
            $report->addValue("Nodejs versions", $versionFromNvmrc);

            $yaml->addStepFromTemplates([
                "use-node", "node-version", "install-dependencies-npm", "execute-npm-test"
            ]);
            $report->addValueInfo("Install dependencies", 'Npm/Nodejs');
            $report->addValueInfo("Install dependencies", 'Npm/Nodejs');
            $report->addValueInfo("Code Quality", 'npm run test');
        }


        $yamlFile = GuesserFiles::generateYamlFilename(
            GuesserFiles::getGithubWorkflowDirectory($projectDir),
            $yaml->getName()
        );
        $this->table(["Report", "Status"], $report->toArrayLabelValue(), "symfony-style-guide");
        if ($showOption) {
            $this->line($yaml->toString());
        }
        if ($saveOption) {
            if ($yaml->saveTo($yamlFile)) {
                $this->info("  --> Saved: " . $yamlFile);
            } else {
                $this->warn("NOT Saved: " . $yamlFile);
            }
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
