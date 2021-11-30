<?php

namespace App\Commands;

use App\Objects\YamlObject;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Swaggest\JsonSchema\Schema;

class CheckWorkflow extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'yaml:check
    {yaml? : Yaml file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check Yaml file';

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
        $yaml->setName("new name for workflow");
        $yaml->setOnPushDefaultBranches();

        //$yaml->addJob();
        //$yaml->setRunsOn(["ubuntu-latest"]);
        $yaml->addMysqlService();
        $yaml->addMatrixOsUbuntuLatest();


        try {
            $json = $yaml->getYamlInJsonFormat();
            $schema = Schema::import('https://json.schemastore.org/github-workflow');
            $schema->in(json_decode($json));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }
        $this->line($yaml->toString());
        //file_put_contents(__DIR__ . "/../../.github/workflows/test.yaml", $yaml->toString());
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
