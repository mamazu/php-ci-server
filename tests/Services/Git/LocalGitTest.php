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
		self::assertFalse($this->localGit->has($this->repository));

		$this->localGit->createRepositoryDirectory($this->repository);
		
		// Execute and assert
		self::assertTrue($this->localGit->has($this->repository));
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
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->localGit->createRepositoryDirectory($this->repository);

		// Execute
		$success = $this->localGit->clone($this->repository);

		// Assert
		self::assertTrue($success);
		self::assertTrue($this->localGit->has($this->repository));
	}

	public function testFetchRepoUpdate()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->localGit->createRepositoryDirectory($this->repository);

		// Execute
		self::assertTrue($this->localGit->clone($this->repository));
		self::assertTrue($this->localGit->fetch($this->repository));

		// Assert
		self::assertTrue($this->localGit->has($this->repository));
	}

	public function testCheckoutRevision()
	{
		// Prepare
		$this->repository->method('getCloneURL')->willReturn('https://github.com/mamazu/php-ci-server.git');
		$this->repository->method('getName')->willReturn('mamazu/php-ci-server');
		$this->repository->method('getRevisionNumber')->willReturn('10a907fd80d9f28f8d4840b71e31ff391af4e48e');

		$this->localGit->createRepositoryDirectory($this->repository);

		// Execute
		self::assertTrue($this->localGit->clone($this->repository));
		self::assertTrue($this->localGit->checkout($this->repository));

		// Assert
		self::assertTrue($this->localGit->has($this->repository));
	}

}