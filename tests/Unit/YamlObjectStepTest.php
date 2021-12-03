<?php

test('Stringify a string', function () {
    $y = \App\Objects\Workflow\YamlObject::make()
    ->addUse(
        "Install PHP versions",
        "shivammathur/setup-php@v2",
        [
            "php-version" => "8.0"
        ]
    );
    expect($y->getSteps())->toBeArray();
    expect($y->getSteps())->toHaveCount(1);

});
