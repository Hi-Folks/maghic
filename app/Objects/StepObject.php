<?php

namespace App\Objects;

use App\Traits\Workflow\Trigger;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class StepObject
{

    public function __construct(
        private mixed $step = []
    ) {
    }
    public static function make(): self
    {
        return new self();
    }

    public function getStep(): array
    {
        return $this->step;
    }


    public function uses(string $uses): self
    {
        $this->step["uses"] = $uses;
        return $this;
    }
    public function usesCheckout(): self
    {
        return $this->uses("actions/checkout@v2");
    }
    public function runs(string $name, string $run): self
    {
        $this->step["name"] = $name;
        $this->step["run"] = $run;
        return $this;
    }
}
