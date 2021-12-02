<?php

namespace App\Objects;

use App\Traits\Workflow\Trigger;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class StepPhpObject extends StepAbstract
{




    public function version(): parent
    {
        return $this->runs("php version", "php -v");
    }

    public function installDependencies(): parent
    {
        return $this->runs(
            "Install Dependencies",
            "composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist"
        );
    }

    public function executeCodeSniffer(): parent
    {
        return $this->runs(
            "Execute Code Sniffer via phpcs",
            "vendor/bin/phpcs --standard=PSR12 app"
        );
    }

    public function executeStaticCodeAnalysis(): parent
    {
        return $this->runs(
            "Execute Static Code Analysis",
            "vendor/bin/phpstan analyse -c ./phpstan.neon --no-progress"
        );
    }

    public function useSetupPhp($version = "8.0"): parent
    {
        return $this->uses(
            "Install PHP versions",
            "shivammathur/setup-php@v2",
            [
                "php-version" => $version
            ]
        );
    }
}
