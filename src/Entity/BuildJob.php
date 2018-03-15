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

	/** @var string */
	private $creator;

	/** @var LogFile */
	private $logFile;

	public function __construct(VCSRepositoryInterface $repository, string $creator)
	{
		$this->repository = $repository;
		$this->creator    = $creator;

		$this->states = new ArrayCollection();
		$this->initialize();
	}

	private function initialize()
	{
		if ($this->states->count() === 0) {
			$pendingState = new BuildState($this, BuildStateInterface::STATUS_PENDING);
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

	public function isDone(): bool
	{
		return $this->getState()->getName() === BuildStateInterface::STATUS_DONE;
	}

	public function isCanceled(): bool
	{
		return $this->getState()->getName() === BuildStateInterface::STATUS_CANCELED;
	}

	public function setStateFromString(string $statusString): void
	{
		$this->setState(new BuildState($this, $statusString));
	}

	//endregion

	public function setCreator(string $creator): void
	{
		$this->creator = $creator;
	}

	public function getCreator(): string
	{
		return $this->creator;
	}

	public function getLogFile(): ?LogFile
	{
		return $this->logFile;
	}

	public function addLogFile(LogFile $file): void
	{
		$this->logFile = $file;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}
}
