<?php

namespace App\Objects\Workflow;

abstract class StepAbstract
{



    final public function __construct(
        protected mixed $step = []
    ) {
    }
    public static function make(): static
    {
        return new static();
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
    public function runs(string $name, string $run /*, array $with = []*/): self
    {
        $this->step["name"] = $name;
        $this->step["run"] = $run;
        /*
        if (count($with) > 0) {
            $this->step["with"] = $with;
        }
        */
        return $this;
    }
}
