<?php

namespace App\Tests\Services\LocalBuilding;

use App\Entity\VCSRepositoryInterface;
use App\Service\LocalBuilding\LocalGit;
use App\Service\LocalBuilding\LocalGitInterface;
use App\Service\LocalBuilding\CommandExecutorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class LocalGitTest extends TestCase
{
	/** @var LocalGitInterface */
	private $localGit;

	/** @var Filesystem */
	private $fileSystem;

	/** @var string */
	private $baseDir;

	/** @var VCSRepositoryInterface */
	private $repository;

	/** @var CommandExecutorInterface */
	private $commandExecutor;

	/** @var array */
	private $data = [];

	public function setUp()
	{
		$baseDir = __DIR__ . '/../../test_data';

		$this->fileSystem = self::createMock(FileSystem::class);

		$this->repository = self::createMock(VCSRepositoryInterface::class);
		$this->commandExecutor = self::createMock(CommandExecutorInterface::class);

		$this->localGit = new LocalGit($baseDir, $this->commandExecutor, $this->fileSystem);

		$this->setupFileSystem($baseDir);
	}

	private function setupFileSystem(string $baseDir)
	{
		$fileSystem = new FileSystem();

		if ($fileSystem->exists($baseDir)) {
			$fileSystem->remove($baseDir);
		}

		$this->fileSystem->mkdir($baseDir);
	}

	private function setupRepository(string $name)
	{
		$this->repository->method('getName')->willReturn($name);
		$this->localGit->createRepositoryDirectory($this->repository);
	}

	/** @dataProvider dataHasRepository */
	public function testHasRepository(string $repositoryName)
	{
		// Prepare
		$this->repository->method('getName')->willReturn($repositoryName);

		self::assertFalse($this->localGit->has($this->repository));
		$this->localGit->createRepositoryDirectory($this->repository);
		
		// Execute
		$has = $this->localGit->has($this->repository);

		// Assert
		self::assertTrue($has);
	}

	public function dataHasRepository() : array
	{
		return [
			'empty name' => [''],
			'some name' => ['test_abc'],
			'a real repo name' => ['mamazu/repo-test'],
		];
	}

	public function testCloneCreatesRepo()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('clone_url');
		$this->setupRepository('some_repo_name');

		$this->commandExecutor->method('execute')->willReturnCallback(function (string $command) {
			self::assertEquals($command, 'git clone clone_url .');
			return true;
		});

		// Execute
		$success = $this->localGit->clone($this->repository);

		// Assert
		self::assertTrue($success);
		self::assertTrue($this->localGit->has($this->repository));
	}

	public function testFetchRepoUpdate()
	{
		// Prepare
		$this->setupRepository('some_repo_name');

		$this->commandExecutor->method('execute')->willReturnCallback(function (string $command) {
			self::assertEquals($command, 'git fetch -a');
			return true;
		});

		// Execute
		self::assertTrue($this->localGit->fetch($this->repository));

		// Assert
		self::assertTrue($this->localGit->has($this->repository));
	}

	public function testCheckoutRevision()
	{
		// Prepare
		$this->setupRepository('some_repo_name');
		$this->repository->method('getRevisionNumber')->willReturn('some_revision');

		$this->commandExecutor->method('execute')->willReturnCallback(function (string $command) {
			self::assertEquals('git checkout some_revision', $command);
			return true;
		});

		// Execute
		self::assertTrue($this->localGit->checkout($this->repository));

		// Assert
		self::assertTrue($this->localGit->has($this->repository));
	}

}