<?php

namespace App\Commands;

use App\Objects\YamlObject;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

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

        $name = $this->ask("What is the Workflow name?");
        $yaml->setName($name);
        $event = $this->anticipate('Which event?', ["push", "pullrequest"]);
        if ($event === "push") {
            $yaml->setOnPushDefaultBranches();
        }
        if ($event === "pull_request") {
            $yaml->setOnPullrequestDefaultBranches();
        }
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
