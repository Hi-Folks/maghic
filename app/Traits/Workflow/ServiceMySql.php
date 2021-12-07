<?php

namespace App\Traits\Workflow;

use Illuminate\Support\Arr;

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
        $this->setYamlKey("env.MYSQL_IMAGE", $image);
        $this->addServiceFromTemplate("mysql");
        $this->setImage($image);
        return $this;
    }

    public function addServiceFromTemplate($name): self
    {
        $jobKeyServices = $this->getCurrentJobKey("services");
        $services =  $this->getYamlKey($jobKeyServices);
        $services[$name] = Arr::get($this->templateYaml, "services." . $name);
        return $this->setYamlKey($jobKeyServices, $services);
    }
}
