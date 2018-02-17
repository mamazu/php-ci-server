<?php

namespace App\Tests\Services\Git;

use App\Entity\VCSRepositoryInterface;
use App\Services\LocalBuilding\LocalGit;
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

	public function setUp()
	{
		$this->baseDir = __DIR__ . '/../../test_data';

		$this->fileSystem = new Filesystem();
		$this->fileSystem->remove($this->baseDir);
		$this->fileSystem->mkdir($this->baseDir);

		$this->repository = self::createMock(VCSRepositoryInterface::class);
		$this->localGit = new LocalGit($this->baseDir, $this->repository);

	}

	/** @dataProvider dataHasRepository */
	public function testHasRepository(string $repositoryName)
	{
		// Prepare
		$this->repository->method('getName')->willReturn($repositoryName);
		self::assertFalse($this->localGit->has($repositoryName));

		$this->localGit->createRepositoryDirectory($repositoryName);
		
		// Execute and assert
		self::assertTrue($this->localGit->has($repositoryName));
	}

	public function dataHasRepository() : array
	{
		return [
			'empty name' => ['_empty'],
			'some name' => ['test_abc'],
			'a real repo name' => ['mamazu/repo-test'],
		];
	}

	public function testCloneCreatesRepo()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->localGit->createRepositoryDirectory();

		// Execute
		$success = $this->localGit->clone();

		// Assert
		self::assertTrue($success);
		self::assertTrue($this->localGit->has());
	}

	public function testFetchRepoUpdate()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->localGit->createRepositoryDirectory();

		// Execute
		self::assertTrue($this->localGit->clone());
		self::assertTrue($this->localGit->fetch());

		// Assert
		self::assertTrue($this->localGit->has());
	}

	public function testCheckoutRevision()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->repository->method('getRevisionNumber')->willReturn('10a907fd80d9f28f8d4840b71e31ff391af4e48e');

		$this->localGit->createRepositoryDirectory();

		// Execute
		self::assertTrue($this->localGit->clone());
		self::assertTrue($this->localGit->checkout());

		// Assert
		self::assertTrue($this->localGit->has());
	}

}