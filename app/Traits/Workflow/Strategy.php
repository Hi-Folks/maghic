<?php

namespace App\Traits\Workflow;

trait Strategy
{

    private function getYamlKeyStrategyMatrix($key): string
    {
        return $this->getCurrentJobKey("strategy.matrix." . $key);
    }

    private function getYamlKeyStrategyMatrixOs(): string
    {
        return $this->getCurrentJobKey("strategy.matrix.os");
    }
    public function getMatrixOs()
    {
        return $this->getYamlKey($this->getYamlKeyStrategyMatrixOs());
    }
    public function setMatrix(string $key, array $value = []): self
    {
        return $this->setYamlKey($this->getYamlKeyStrategyMatrix($key), $value);
    }

    public function setMatrixOs(array $operatingSystems = ['ubuntu-latest']): self
    {
        $this->setRunsOnMatrix();
        return $this->setYamlKey($this->getYamlKeyStrategyMatrixOs(), $operatingSystems);
    }
    public function addMatrixOs(string $operatingSystem = 'ubuntu-latest'): self
    {
        $matrixOs = $this->getMatrixOs();
        $matrixOs = is_null($matrixOs) ? [] : $matrixOs;

        if (! in_array($operatingSystem, $matrixOs)) {
            $matrixOs[] = $operatingSystem;
        }
        $this->setRunsOnMatrix();
        return $this->setYamlKey($this->getYamlKeyStrategyMatrixOs(), $matrixOs);
    }
    public function addMatrixOsUbuntuLatest(): self
    {
        return $this->addMatrixOs("ubuntu-latest");
    }
    public function addMatrixOsMacosLatest(): self
    {
        return $this->addMatrixOs("macos-latest");
    }
    public function addMatrixOsWindowsLatest(): self
    {
        return $this->addMatrixOs("windows-latest");
    }
}
