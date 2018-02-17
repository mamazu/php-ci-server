<?php

declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use App\Services\LocalBuilding\JobBuilder;
use App\Services\LocalBuilding\LocalGitInterface;

class JobBuilderTest extends TestCase
{
	/** @var JobBuilderInterface */
	private $jobBuilder;

	/** @var LocalGitInterface */
	private $localGit;

	public function setup()
	{
		$this->localGit = self::createMock(LocalGitInterface::class);
		$this->jobBuilder = new JobBuilder($this->localGit);
	}

	public function testBuildWithExistingRepo()
	{
		$this->localGit->method('has')->willReturn(true);
	}
}