<?php

namespace App\Traits\Workflow;

use App\Objects\StepObject;

trait Step
{
    private function getYamlKeySteps(): string
    {
        return $this->getCurrentJobKey("steps");
    }
    public function getSteps()
    {
        return $this->getYamlKey($this->getYamlKeySteps());
    }
    public function setSteps($steps): self
    {

        return $this->setYamlKey($this->getYamlKeySteps(), $steps);
    }

    public function addSteps($newSteps): self
    {
        $steps =  $this->getYamlKey($this->getYamlKeySteps());
        if (is_null($steps)) {
            $steps = [];
        }
        //dd($newSteps);
        foreach ($newSteps as $step) {
            $steps[] = $step->getStep();
        }

        return $this->setSteps($steps);
    }

    public function addRun($name, $commands)
    {
        return $this->addSteps(
            [
                StepObject::make()->runs(
                    $name,
                    $commands
                )
            ]
        );
    }
    public function addUse($name, $commands, $with = [])
    {
        return $this->addSteps(
            [
                StepObject::make()->uses(
                    $name,
                    $commands,
                    $with
                )
            ]
        );
    }
}
