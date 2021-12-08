<?php

namespace App\Objects\Workflow;

use App\Traits\Workflow\Head;
use App\Traits\Workflow\Job;
use App\Traits\Workflow\ServiceMySql;
use App\Traits\Workflow\Step;
use App\Traits\Workflow\Strategy;
use App\Traits\Workflow\Trigger;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class YamlObject
{
    use Head;
    use Trigger;
    use Job;
    use ServiceMySql;
    use Strategy;
    use Step;

    protected array $templateYaml = [];

    public function __construct(
        private mixed $yaml = []
    ) {
        $this->setCurrentJobName("build");
        $this->loadFromTemplate();
    }

    public static function make(): self
    {
        return new self();
    }

    public function getYaml()
    {
        return $this->yaml;
    }
    public function getYamlStringFormat()
    {
        return json_encode($this->getYaml());
    }

    public static function load($yamlFile)
    {
        $yaml = Yaml::parseFile($yamlFile, Yaml::PARSE_OBJECT);
        $yamlObject = new self($yaml);
        $yamlObject->setCurrentJobName($yamlObject->getFirstJobName());
        return $yamlObject;
    }

    public function loadFromTemplate()
    {
        $this->templateYaml = Yaml::parseFile(app()->configPath("template-steps.yaml"));
    }

    public function toString(): string
    {
        return Yaml::dump($this->yaml, 5, 4);
    }

    public function saveTo($filename, $overwrite = false, $dryRun = false): bool
    {
        if (! $overwrite and  file_exists($filename)) {
            return false;
        }

        $info = new SplFileInfo($filename);
        $path = $info->getPath();
        if ($dryRun) {
            return false;
        }
        if (is_dir($path) or ($path === "")) {
            File::put($filename, $this->toString());
            return true;
        }
        return false;
    }



    public function getOn()
    {
        return $this->getYamlKey("on");
    }
    public function getOnString()
    {
        return $this->stringify($this->getOn());
    }

    public function hasKey($key): bool
    {
        return !is_null($this->getYamlKey($key));
    }





    public function getYamlKey($key)
    {
        return Arr::get($this->yaml, $key);
    }
    public function setYamlKey($key, $value)
    {
        Arr::set($this->yaml, $key, $value);
        return $this;
    }

    public function stringify($element): string
    {
        if (is_array($element)) {
            if (Arr::isAssoc($element)) {
                return implode(",", array_keys($element));
            }
            return implode(",", $element);
        }
        if (is_null($element)) {
            return "";
        }
        return $element;
    }
}
