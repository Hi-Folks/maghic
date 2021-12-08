<?php

test('parsing yaml 1', function () {
    $this->artisan('yaml:check', ['yaml' => __DIR__ . "/../data/001.yaml"])
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(0);
});
test('parsing yaml 2', function () {
    $this->artisan('yaml:check', ['yaml' => __DIR__ . "/../data/002.yaml"])
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(0);
});
test('parsing yaml 3', function () {
    $this->artisan('yaml:check', ['yaml' => __DIR__ . "/../data/003.yaml"])
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(0);
});
test('parsing yaml with no file', function () {
    $this->artisan('yaml:check')
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(1);
})->throws(Exception::class);
test('Yaml Check errors', function () {
    $this->artisan('yaml:check', ['yaml' => __DIR__ . "/../data/004.yaml"])
        ->assertExitCode(1);
});
test('Yaml Check show', function () {
    $this->artisan('yaml:check', ['show', 'yaml' => __DIR__ . "/../data/001.yaml"])
        ->assertExitCode(0);
});
/*
test('Yaml Edit', function () {
    $this->artisan('yaml:edit')
        ->expectsQuestion('What is the Workflow name?', 'My Test Workflow')
        ->expectsQuestion('Which event?', 'push')
        ->assertExitCode(0);
});
test('Yaml Edit with file', function () {
    $this->artisan('yaml:edit', ['yaml' => __DIR__ . "/../data/001.yaml"])
        ->expectsQuestion('What is the Workflow name?', 'My Test Workflow')
        ->expectsQuestion('Which event?', 'pull_request')
        ->assertExitCode(0);
});
*/


test('parsing yaml2', function () {
    $y = \App\Objects\Workflow\YamlObject::load(__DIR__ . "/../data/001.yaml");
    $this->expect($y->getName())->toBe("Manually triggered workflow");
});

test('Yaml Edit with file', function () {
    $this->artisan('yaml:edit', ['yaml' => __DIR__ . "/../data/001.yaml"])
        //->expectsQuestion('What is the Workflow name?', 'My Test Workflow')
        //->expectsQuestion('Which event?', 'pull_request')
        ->assertExitCode(0);
});
test('Yaml Edit with no file', function () {
    Storage::fake('local');
    $this->artisan('yaml:edit', ["--dry-run" => 1, "--saveto" => "maghic1-test.yml"])
        //->expectsQuestion('What is the Workflow name?', 'My Test Workflow')
        //->expectsQuestion('Which event?', 'pull_request')
        ->assertExitCode(0);
});
test('Yaml Edit with no cache', function () {
    \Illuminate\Support\Facades\Cache::forget('cache-schema-yaml');
    Storage::fake('local');

    $this->artisan('yaml:edit', ["--dry-run" => 1,"--saveto" => "maghic2-test.yml"])
        //->expectsQuestion('What is the Workflow name?', 'My Test Workflow')
        //->expectsQuestion('Which event?', 'pull_request')
        ->assertExitCode(0);
});
