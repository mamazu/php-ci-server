<?php

namespace App\Services\LocalBuilding;

use App\Entity\BuildJobInterface;
use App\Entity\VCSRepositoryInterface;


class JobBuilder implements JobBuilderInterface
{
	/** @var GitInterface */
	private $gitInterface;

	public function __construct(LocalGitInterface $gitInterface)
	{
		$this->gitInterface = $gitInterface;
	}

	public function build(BuildJobInterface $buildJob) : bool
	{
		return false;
	}

	private function prepareSourceCode(VCSRepositoryInterface $repository)
	{
		if ($this->gitInterface->has($repository)) {
			$this->gitInterface->fetch();
		} else {
			$this->gitInterface->clone();
		}
		
		$this->gitInterface->checkout();
	}
}