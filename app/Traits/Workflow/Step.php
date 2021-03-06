<?php

namespace App\Traits\Workflow;

use App\Objects\Workflow\StepObject;
use App\Objects\Workflow\StepPhpObject;
use Illuminate\Support\Arr;

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
    public function setStepsYaml($steps): self
    {

        return $this->setYamlKey($this->getYamlKeySteps(), $steps);
    }
    public function setSteps($newSteps): self
    {
        $steps = [];
        foreach ($newSteps as $step) {
            $steps[] = $step->getStep();
        }

        return $this->setStepsYaml($steps);
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

        return $this->setStepsYaml($steps);
    }

    public function addStepFromTemplates($array): self
    {
        foreach ($array as $name) {
            $this->addStepFromTemplate($name);
        }
        return $this;
    }
    public function addStepFromTemplate($name): self
    {
        $steps =  $this->getYamlKey($this->getYamlKeySteps());
        //dd(Arr::get($this->templateYaml, "steps." . $name));
        $steps[] = Arr::get($this->templateYaml, "steps." . $name);
        return $this->setStepsYaml($steps);
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

    public function checkout(): self
    {
        return $this->addSteps(
            [
                StepPhpObject::make()->usesCheckout()
            ]
        );
    }
}
