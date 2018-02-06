<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class BuildJob implements BuildJobInterface
{
    /** @var int */
    private $id;

    /** @var string */
    private $commitId;

    /** @var string */
    private $repositoryName;

    /** @var BuildStateChanges[] */
    private $states;

    public function __construct(
        string $commitId,
        string $repoName
    ) {
        $this->commitId = $commitId;
        $this->repositoryName = $repoName;

        $this->states = new ArrayCollection();
        $this->initialize();
    }

    private function initialize()
    {
        if ($this->states->count() === 0) {
            $pendingState = new BuildState($this, BuildJobInterface::STATUS_PENDING);
            $this->states->add($pendingState);
        }
    }

    public function getRepositoryName() : string
    {
        return $this->repositoryName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCommitId() : string
    {
        return $this->commitId;
    }

    public function getState() : BuildState
    {
        return $this->states->last();
    }

    public function getStates() : array
    {
        return $this->states->toArray();
    }

    public function setState(string $state) : bool
    {
        if (!in_array($state, $this->getAllStates())) {
            return false;
        }

        $this->states->add(new BuildState($this, $state));
        return true;
    }

    public function getAllStates() : array
    {
        return [
            BuildJobInterface::STATUS_PENDING,
            BuildJobInterface::STATUS_INPROGRESS,
            BuildJobInterface::STATUS_DONE
        ];
    }
}
