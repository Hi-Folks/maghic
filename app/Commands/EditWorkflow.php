<?php

namespace App\Commands;

use App\Objects\StepPhpObject;
use App\Objects\YamlObject;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Objects\StepObject;

class EditWorkflow extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'yaml:edit
    {yaml? : Yaml file}
    {--overwrite : Overwrite}
    {--show : Show the generated Yaml Workflow file}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit Trigger';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $yamlFile = $this->argument('yaml');
        if (is_null($yamlFile)) {
            $yaml = YamlObject::make();
            $yamlFile = app()->basePath() . DIRECTORY_SEPARATOR . uniqid("workflow_maghic_") . ".yaml";
        } else {
            $yaml = YamlObject::load($yamlFile);
        }
        $overwrite = $this->option('overwrite');
        $showYaml = $this->option('show');


        $this->title("Maghic: check file");
        $this->line("Current Directory" . getcwd());
        $this->line("Workflow name: " . $yaml->getName());
        $this->line("PUSH Branches: " . $yaml->getOnPushBranchesString());
        $this->line("PR   Branches: " . $yaml->getOnPullrequestBranchesString());
        $yaml->setName("new name for workflow")
            ->setOnPushBranches(["main"])
            ->addMatrixOsUbuntuLatest()
            ->setSteps(
                [
                    StepPhpObject::make()->usesCheckout(),
                    StepPhpObject::make()->version(),
                    StepPhpObject::make()->useSetupPhp("8.0"),
                    StepPhpObject::make()->installDependencies(),
                    StepPhpObject::make()->executeCodeSniffer(),
                    StepPhpObject::make()->executeStaticCodeAnalysis()
                ]
            );



        $filename = $yamlFile;
        if ($yaml->saveTo($filename, $overwrite)) {
            $this->line("Saved: " . $filename);
        } else {
            $this->warn("NOT Saved: " . $filename);
        }
        if ($showYaml) {
            $this->line($yaml->toString());
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
