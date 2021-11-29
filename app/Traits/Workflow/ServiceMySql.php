<?php

namespace App\Traits\Workflow;

trait ServiceMySql
{
    public function getImage()
    {
        return $this->getYamlKey(
            $this->getCurrentJobKey("services.mysql.image")
        );
    }

    public function setImage(string $image = "mysql:latest"): self
    {
        return $this->setYamlKey(
            $this->getCurrentJobKey("services.mysql.image"),
            $image
        );
    }

    public function addMysqlService(
        string $image = "mysql:latest"
    ): self {
        $jobKey = $this->getCurrentJobKey("services.mysql");
        $this->setYamlKey(
            $jobKey . ".image",
            $image
        );
        $this->setYamlKey(
            $jobKey . ".env",
            [
                'MYSQL_ALLOW_EMPTY_PASSWORD' => 'yes',
                'MYSQL_DATABASE' => 'db_test_laravel',
            ]
        );
        $this->setYamlKey(
            $jobKey . ".ports",
            ['33306:3306']
        );
        $this->setYamlKey(
            $jobKey . ".options",
            '--health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3'
        );

        return $this;
    }
}
