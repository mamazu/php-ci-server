<?php

declare (strict_types = 1);

use App\Entity\BuildJobInterface;
use App\Entity\BuildStateInterface;
use App\Service\LocalBuilding\JobBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;
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
		$entityManager = self::createMock(EntityManagerInterface::class);
		$this->jobBuilder = new JobBuilder($this->localGit, $entityManager);
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
		$buildJob = new BuildJob($repo, 'test');

		self::assertTrue($this->jobBuilder->build($buildJob));
		self::assertEquals(BuildStateInterface::STATUS_DONE, $buildJob->getState()->getName());
	}

	public function testBuildWithNewRepo()
	{
		$this->localGit->method('has')->willReturn(false);

		$repo = $this->createRepository();
		$buildJob = new BuildJob($repo, 'test');

		self::assertTrue($this->jobBuilder->build($buildJob));
		self::assertEquals(BuildStateInterface::STATUS_DONE, $buildJob->getState()->getName());
	}
}