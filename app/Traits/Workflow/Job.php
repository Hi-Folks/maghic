<?php

namespace App\Traits\Workflow;

trait Job
{
    public string $currentJobName;

    public function getJobs()
    {
        return $this->getYamlKey("jobs");
    }
    public function getJobsString(): string
    {
        return $this->stringify($this->getJobs());
    }

    public function getCurrentJob()
    {
        return $this->getYamlKey("jobs." . $this->currentJobName);
    }

    public function getFirstJobName()
    {
        $jobsArray = $this->getYamlKey("jobs");
        if (is_array($jobsArray)) {
            return array_key_first($jobsArray);
        }
        return $this->currentJobName;
    }

    public function addJob($jobName = "build")
    {
        $this->setYamlKey("jobs." . $jobName, []);
        $this->setCurrentJobName($jobName);
        return $this;
    }


    public function setCurrentJobName(string $name): self
    {
        $this->currentJobName = $name;
        return $this;
    }

    public function getCurrentJobKey($subKey = ""): string
    {
        return ($subKey === "") ?
            "jobs." . $this->currentJobName :
            "jobs." . $this->currentJobName . "." . $subKey;
    }

    public function setRunsOn(array $operatingSystems = ['ubuntu-latest']): self
    {
        return $this->setYamlKey($this->getCurrentJobKey("runs-on"), implode(",", $operatingSystems));
    }
    public function setRunsOnMatrix(): self
    {
        return $this->setYamlKey($this->getCurrentJobKey("runs-on"), '${{ matrix.os }}');
    }
    public function getRunsOn(): array
    {
        $runsOn = $this->getYamlKey($this->getCurrentJobKey("runs-on"));
        if (is_string($runsOn)) {
            $runsOn = [ $runsOn ];
        }
        return $runsOn;
    }
    public function getRunsOnString(): string
    {
        return $this->stringify($this->getRunsOn());
    }
}
