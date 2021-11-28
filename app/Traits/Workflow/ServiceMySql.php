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
}
