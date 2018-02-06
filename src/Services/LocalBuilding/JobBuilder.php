<?php

namespace App\Services\LocalBuilding;

use App\Entity\BuildJobInterface;


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
}