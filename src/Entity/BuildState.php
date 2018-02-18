<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

class BuildState implements BuildStateInterface
{
	/** @var int */
	private $id;

	/** @var BuildJobInterface */
	private $buildJob;

	/** @var string */
	private $name;

	/** @var DateTime */
	private $time;

	public function __construct(BuildJobInterface $buildJob, string $state)
	{
		$this->buildJob = $buildJob;
		$this->name = $state;
		$this->time = new DateTime('now');
	}

	public function getId()
	{
		return $this->id;
	}

	public function setBuildJob(BuildJobInterface $buildJob)
	{
		$this->buildJob = $buildJob;
	}

	public function getBuildJob() : BuildJobInterface
	{
		return $this->buildJob;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getTime() : DateTime
	{
		return $this->time;
	}

}