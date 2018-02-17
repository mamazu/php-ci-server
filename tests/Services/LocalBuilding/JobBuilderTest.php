<?php

declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use App\Service\LocalBuilding\JobBuilder;
use App\Service\LocalBuilding\LocalGitInterface;
use App\Entity\VCSRepository;
use App\Service\VCSRepositoryValidatorInterface;
use App\Entity\BuildJob;

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

	private function createRepository()
	{
		$validator = self::createMock(VCSRepositoryValidatorInterface::class);

		$cloneURL = '';
		$repoName = '';
		$revision = '';

		return new VCSRepository($validator, $cloneURL, $repoName, $revision);
	}

	public function testBuildWithExistingRepo()
	{
		$this->localGit->method('has')->willReturn(true);
		$repo = $this->createRepository();
		$buildJob = new BuildJob(1);

		self::assertTrue($this->jobBuilder->build($repo));
	}
}