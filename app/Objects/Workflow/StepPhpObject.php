<?php

namespace App\Objects\Workflow;

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

    public function executeCodeSniffer($type = "squizlabs/php_codesniffer"): parent
    {
        switch ($type) {
            case "squizlabs/php_codesniffer":
                return $this->runs(
                    "Execute Code Sniffer via phpcs",
                    "vendor/bin/phpcs --standard=PSR12 app"
                );
        }
        return $this;
    }

    public function executeStaticCodeAnalysis($type = "phpstan/phpstan"): parent
    {
        switch ($type) {
            case "phpstan/phpstan":
                return $this->runs(
                    "Execute Static Code Analysis",
                    "vendor/bin/phpstan analyse -c ./phpstan.neon --no-progress"
                );
        }
        return $this;
    }

    public function useSetupPhpMatrix(): parent
    {
        return $this->useSetupPhp('${{ matrix.php }}');
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
