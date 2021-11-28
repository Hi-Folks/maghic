<?php

test('parsing yaml', function () {
    $this->artisan('yaml:check', ['yaml' => __DIR__ . "/../data/001.yaml"])
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(0);
});
test('parsing yaml with no file', function () {
    $this->artisan('yaml:check')
        //->expectsOutput('Not enough arguments (missing: "yaml").')
        ->assertExitCode(0);
});
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

test('parsing yaml2', function () {
    $y = \App\Objects\YamlObject::load(__DIR__ . "/../data/001.yaml");
    $this->expect($y->getName())->toBe("Manually triggered workflow");
});
