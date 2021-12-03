<?php

namespace App\Objects\Workflow;

use App\Traits\Workflow\Head;
use App\Traits\Workflow\Job;
use App\Traits\Workflow\ServiceMySql;
use App\Traits\Workflow\Step;
use App\Traits\Workflow\Strategy;
use App\Traits\Workflow\Trigger;
use Illuminate\Support\Arr;
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

    public function __construct(
        private mixed $yaml = []
    ) {
        $this->setCurrentJobName("build");
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

    public function toString(): string
    {
        return Yaml::dump($this->yaml, 5, 4);
    }

    public function saveTo($filename, $overwrite = false): bool
    {
        if (! $overwrite) {
            if (file_exists($filename)) {
                return false;
            }
        }

        $info = new SplFileInfo($filename);

        if (is_dir($info->getPath())) {
            file_put_contents($filename, $this->toString());
        }
        return true;
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
