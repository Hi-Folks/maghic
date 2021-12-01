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


    public function uses(string $name, string $uses, array $with = []): self
    {
        if ($name != "") {
            $this->step["name"] = $name;
        }
        $this->step["uses"] = $uses;
        if (count($with) > 0) {
            $this->step["with"] = $with;
        }
        return $this;
    }
    public function usesCheckout(): self
    {
        return $this->uses("Checkout", "actions/checkout@v2");
    }
    public function runs(string $name, string $run, array $with = []): self
    {
        $this->step["name"] = $name;
        $this->step["run"] = $run;
        if (count($with) > 0) {
            $this->step["with"] = $with;
        }
        return $this;
    }
}
