<?php

test('Get Matrix Os', function () {
$y = \App\Objects\Workflow\YamlObject::make()
->addMatrixOsUbuntuLatest();
expect($y->getMatrixOs())->toBe(["ubuntu-latest"]);
$y->setMatrixOs(["ubuntu-18.04"]);
expect($y->getMatrixOs())->toBe(["ubuntu-18.04"]);
$y->addMatrixOsUbuntuLatest();
expect($y->getMatrixOs())->toBe(["ubuntu-18.04", "ubuntu-latest"]);
$y->addMatrixOsMacosLatest();
$y->addMatrixOsWindowsLatest();
    expect($y->getMatrixOs())->toBe(["ubuntu-18.04", "ubuntu-latest", "macos-latest", "windows-latest"]);
});
