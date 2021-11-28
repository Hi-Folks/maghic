<?php

namespace App\Traits\Workflow;

trait Head
{
    public function getName()
    {
        return $this->getYamlKey("name");
    }

    public function setName(string $name): self
    {
        return $this->setYamlKey("name", $name);
    }
}
