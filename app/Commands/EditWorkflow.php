<?php

namespace App\Commands;

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
    {yaml? : Yaml file}';

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
        } else {
            $yaml = YamlObject::load($yamlFile);
        }


        $this->title("Maghic: check file");
        $this->line("Current Directory" . getcwd());
        $this->line("Workflow name: " . $yaml->getName());
        $this->line("PUSH Branches: " . $yaml->getOnPushBranchesString());
        $this->line("PR   Branches: " . $yaml->getOnPullrequestBranchesString());
        $yaml->setName("new name for workflow")
            ->setOnPushDefaultBranches()
            //->addMysqlService()
            ->addMatrixOsUbuntuLatest()
            ->addSteps(
                [
                    StepObject::make()->usesCheckout(),
                    StepObject::make()->runs("php version", "php -v"),
                ]
            )
            ->addUse(
                "Install PHP versions",
                "shivammathur/setup-php@v2",
                [
                    "php-version" => "8.0"
                ]
            )
            ->addRun(
                "Install Dependencies",
                "composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist"
            )
            ->addRun("Execute Code Sniffer via phpcs", "vendor/bin/phpcs --standard=PSR12 app");

        $this->line($yaml->toString());
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
