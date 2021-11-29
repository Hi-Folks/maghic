<?php

test('Get Service Image', function () {
    $y = \App\Objects\YamlObject::load(__DIR__ . "/../data/003.yaml");
    expect($y->setCurrentJobName("laravel-tests")->getImage())->toBe("mysql:5.7");
});
test('GetSet Service Image', function () {
    $y = \App\Objects\YamlObject::load(__DIR__ . "/../data/003.yaml");
    expect($y->setCurrentJobName("laravel-tests")->getImage())->toBe("mysql:5.7");
    expect($y->setImage("mysql:latest"));
    expect($y->getImage())->toBe("mysql:latest");
});

test('Add Mysql service', function () {
    $y = \App\Objects\YamlObject::make()
        ->setName("Test Mysql Service")
        ->setOnPushDefaultBranches()
        ->addMysqlService();
    expect($y->getImage())->toBe("mysql:latest");

});

