<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class BuildJob implements BuildJobInterface
{
	/** @var int */
	private $id;

	/** @var VCSRepositoryInterface */
	private $repository;

	/** @var BuildState[] */
	private $states;

	public function __construct(VCSRepositoryInterface $repository)
	{
		$this->repository = $repository;

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

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getRepository(): VCSRepositoryInterface
	{
		return $this->repository;
	}

	//region State Logic
	public function getState(): BuildStateInterface
	{
		return $this->states->last();
	}

	public function getStates(): array
	{
		return $this->states->toArray();
	}

	public function setState(BuildStateInterface $buildState): void
	{
		$this->states->add($buildState);
	}

	public function getAllStates(): array
	{
		return [
			BuildJobInterface::STATUS_PENDING,
			BuildJobInterface::STATUS_INPROGRESS,
			BuildJobInterface::STATUS_DONE
		];
	}
	//endregion
}
