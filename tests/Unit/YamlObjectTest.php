<?php

test('Stringify a string', function () {
    $y = \App\Objects\YamlObject::make();
    expect($y->stringify("hello"))->toBe("hello");
});

test('Yaml Object file 001', function () {
    $y = \App\Objects\YamlObject::load(__DIR__ . "/../data/001.yaml");
    expect($y->getName())->toBe("Manually triggered workflow");
    expect($y->getOnPullrequestBranchesString())->toBe("");
    expect($y->getOnPush())->toBeNull();
    expect($y->hasOnPush())->toBeFalse();
    expect($y->hasOnPullrequest())->toBeFalse();
    expect($y->getOnPullrequest())->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("workflow_dispatch");
});
test('Yaml Object file 002', function () {
    $y = \App\Objects\YamlObject::load(__DIR__ . "/../data/002.yaml");
    expect($y->getName())->toBe("PHP Composer");
    expect($y->getOnPullRequestBranchesString())->toBe('$default-branch');
    expect($y->getOnPush())->not()->toBeNull();
    expect($y->hasOnPush())->toBeTrue();
    expect($y->hasOnPullRequest())->toBeTrue();
    expect($y->getOnPullRequest())->not()->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("push,pull_request");
});
test('Yaml Object NEW', function () {
    $y = new \App\Objects\YamlObject();
    $y->setName("My Test Name");
    expect($y->getName())->toBe("My Test Name");

    expect($y->getOnPullRequestBranchesString())->toBe('');
    expect($y->getOnPush())->toBeNull();
    expect($y->hasOnPush())->toBeFalse();
    expect($y->hasOnPullRequest())->toBeFalse();
    expect($y->getOnPullRequest())->toBeNull();
    expect($y->getOn())->toBeNull();
    expect($y->getOnString())->toBe("");
});

test('Yaml Object NEW with on push', function () {
    $y = new \App\Objects\YamlObject();
    $y->setName("My Test Name");
    $y->setOnPushBranches(["main", "develop"]);
    expect($y->getName())->toBe("My Test Name");
    expect($y->getOnPushBranchesString())->toBe('main,develop');
    expect($y->getOnPullRequestBranchesString())->toBe('');
    expect($y->getOnPush())->not()->toBeNull();
    expect($y->hasOnPush())->toBeTrue();
    expect($y->hasOnPullRequest())->toBeFalse();
    expect($y->getOnPullRequest())->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("push");
});

test('Yaml Object NEW with on push and pr', function () {
    $y = new \App\Objects\YamlObject();
    $y->setName("My Test Name");
    $y->setOnPushBranches(["main", "develop"]);
    $y->setOnPullrequestBranches(["main", "develop"]);
    expect($y->getName())->toBe("My Test Name");
    expect($y->getOnPushBranchesString())->toBe('main,develop');
    expect($y->getOnPullRequestBranchesString())->toBe('main,develop');
    expect($y->getOnPush())->not()->toBeNull();
    expect($y->hasOnPush())->toBeTrue();
    expect($y->hasOnPullRequest())->toBeTrue();
    expect($y->getOnPullRequest())->not()->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("push,pull_request");
});


test('Fluent Yaml Object NEW with on push and pr', function () {
    $y = \App\Objects\YamlObject::make()
        ->setName("My Test Name")
        ->setOnPushBranches(["main", "develop"])
        ->setOnPullrequestBranches(["main", "develop"]);
    expect($y->getName())->toBe("My Test Name");
    expect($y->getOnPushBranchesString())->toBe('main,develop');
    expect($y->getOnPullRequestBranchesString())->toBe('main,develop');
    expect($y->getOnPush())->not()->toBeNull();
    expect($y->hasOnPush())->toBeTrue();
    expect($y->hasOnPullRequest())->toBeTrue();
    expect($y->getOnPullRequest())->not()->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("push,pull_request");
});
test('Fluent Yaml Object default branches', function () {
    $y = \App\Objects\YamlObject::make()
        ->setName("My Test Name")
        ->setOnPushDefaultBranches()
        ->setOnPullrequestDefaultBranches();
    expect($y->getName())->toBe("My Test Name");
    expect($y->getOnPushBranchesString())->toBe('$default-branch');
    expect($y->getOnPullRequestBranchesString())->toBe('$default-branch');
    expect($y->getOnPush())->not()->toBeNull();
    expect($y->hasOnPush())->toBeTrue();
    expect($y->hasOnPullRequest())->toBeTrue();
    expect($y->getOnPullRequest())->not()->toBeNull();
    expect($y->getOn())->not()->toBeNull();
    expect($y->getOnString())->toBe("push,pull_request");
});

test('Fluent Yaml Object Runs On', function () {
    $y = \App\Objects\YamlObject::make()
        ->setName("My Test Name")
        ->setOnPushDefaultBranches()
        ->setRunsOn(["ubuntu-latest"]);
    expect($y->getName())->toBe("My Test Name");
    expect($y->getOnPushBranchesString())->toBe('$default-branch');
    expect($y->getRunsOnString())->toBe('ubuntu-latest');
    expect($y->getJobsString())->toBe("build");
    expect($y->getOnString())->toBe("push");
    expect($y->getCurrentJobKey())->toBe("jobs.build");
    expect($y->getCurrentJob())->toBeArray();
    $y->addJob("deploy");
    expect($y->getCurrentJobKey())->toBe("jobs.deploy");
});
