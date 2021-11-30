<?php

namespace App\Commands;

use App\Objects\YamlObject;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
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
    {yaml : Yaml file}
    {--show : show the parsed yaml file}';

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
        $showYaml = $this->option('show');
        $yaml = YamlObject::load($yamlFile);


        $this->title("Maghic: check file");
        $this->line("Check file: " . $yamlFile);
        $this->line("Current Directory" . getcwd());
        try {
            $json = $yaml->getYamlStringFormat();
            /*
            if ($json === false) {
                $this->error("Not valid yaml");
                return self::FAILURE;
            }
            */
            $seconds = 60 * 60 * 3 ; // 3 hours
            $schema = Cache::remember('cache-schema-yaml', $seconds, function () {
                return Schema::import('https://json.schemastore.org/github-workflow');
                //return Schema::import(json_decode(file_get_contents(base_path("github-workflow.json"))));
            });
            $schema->in(json_decode($json));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
        $this->line("Workflow name: " . $yaml->getName());
        $this->line("PUSH Branches: " . $yaml->getOnPushBranchesString());
        $this->line("PR   Branches: " . $yaml->getOnPullrequestBranchesString());

        if ($showYaml) {
            $this->line($yaml->toString());
        }
        return self::SUCCESS;

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
