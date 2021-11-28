<?php

namespace App\Traits\Workflow;

trait Trigger
{

    public function hasOnPush(): bool
    {
        return $this->hasKey("on.push");
    }
    public function hasOnPullrequest()
    {
        return $this->hasKey("on.pull_request");
    }
    public function getOnPush()
    {
        return $this->getYamlKey("on.push");
    }
    public function getOnPullrequest()
    {
        return $this->getYamlKey("on.pull_request");
    }
    public function getOnPushBranches()
    {
        return $this->getYamlKey("on.push.branches");
    }
    public function getOnPullRequestBranches()
    {
        return $this->getYamlKey("on.pull_request.branches");
    }
    public function setOnPushBranches(array $branches): self
    {
        return $this->setYamlKey("on.push.branches", $branches);
    }
    public function setOnPushDefaultBranches(): self
    {
        return $this->setYamlKey("on.push.branches", ['$default-branch']);
    }
    public function setOnPullrequestBranches(array $branches): self
    {
        return $this->setYamlKey("on.pull_request.branches", $branches);
    }
    public function setOnPullrequestDefaultBranches(): self
    {
        return $this->setYamlKey("on.pull_request.branches", ['$default-branch']);
    }
    public function getOnPushBranchesString(): string
    {
        return $this->stringify($this->getOnPushBranches());
    }
    public function getOnPullrequestBranchesString(): string
    {
        return $this->stringify($this->getOnPullrequestBranches());
    }
}
