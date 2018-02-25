<?php
declare (strict_types = 1);

namespace App\Service\LocalBuilding;

use App\Entity\VCSRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\LocalBuilding\CommandExecutorInterface;

class LocalGit implements LocalGitInterface
{
	/** @var string */
	private $rootDir;

	/** @var CommandExecutorInterface */
	private $commandExecutor;

	/** @var Filesystem */
    private $fileSystem;

    public function __construct(string $rootDir, CommandExecutorInterface $commandExectutor)
	{
		$this->rootDir = $rootDir;
		$this->commandExecutor = $commandExectutor;
		$this->fileSystem = new FileSystem();
	}

	/** {@inheritdoc} */
	public function has(VCSRepositoryInterface $repository) : bool
	{
	    $directory = $this->getRepositoryDirectory($repository);
		return $this->fileSystem->exists($directory);
	}

	/** {@inheritdoc} */
	public function createRepositoryDirectory(VCSRepositoryInterface $repository): void
	{
		$this->fileSystem->mkdir($this->getRepositoryDirectory($repository));
	}

	/** {@inheritdoc} */
	public function clone(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();

		$this->createRepositoryDirectory($repository);
		chdir($this->getRepositoryDirectory($repository));

		$repositoryURL = $repository->getCloneURL();
		$success = $this->commandExecutor->execute("git clone $repositoryURL .");

		chdir($previousDirectory);
		return $success;
	}

	/** {@inheritdoc} */
	public function fetch(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory($repository));

		$success = $this->commandExecutor->execute("git fetch -a");

		chdir($previousDirectory);
		return $success;
	}

	public function checkout(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory($repository));
		$revisionNumber = $repository->getRevisionNumber();

		$success = $this->commandExecutor->execute("git checkout $revisionNumber");

		chdir($previousDirectory);
		return $success;
	}

	private function getRepositoryDirectory(VCSRepositoryInterface $repository) : string
	{
		$repositoryName = $repository->getName();

		if (strlen($repositoryName) === 0) {
			$directory = '_empty';
		} else {
			$directory = base64_encode($repositoryName);
		}

		return $this->rootDir . DIRECTORY_SEPARATOR . $directory;
	}
}