<?php
declare (strict_types=1);

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

	public function __construct(
		string $rootDir,
		CommandExecutorInterface $commandExectutor,
		?Filesystem $filesystem = null
	) {
		$this->rootDir         = $rootDir;
		$this->commandExecutor = $commandExectutor;
		$this->fileSystem      = $filesystem ?? new Filesystem();
	}

	/** {@inheritdoc} */
	public function has(VCSRepositoryInterface $repository): bool
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
	public function clone(VCSRepositoryInterface $repository): bool
	{
		$this->createRepositoryDirectory($repository);

		$repositoryURL = $repository->getCloneURL();

		return $this->executeInRepositoryDirectory($repository, "git clone $repositoryURL .");
	}

	/** {@inheritdoc} */
	public function fetch(VCSRepositoryInterface $repository): bool
	{
		return $this->executeInRepositoryDirectory($repository, "git fetch -a");
	}

	public function checkout(VCSRepositoryInterface $repository): bool
	{
		$revisionNumber = $repository->getRevisionNumber();

		return $this->executeInRepositoryDirectory($repository, "git checkout $revisionNumber");
	}

	/**
	 * Executes a command in the repository directory and switches back to the directory it was in.
	 *
	 * @param VCSRepositoryInterface $repository
	 * @param string                 $command
	 *
	 * @return bool
	 */
	private function executeInRepositoryDirectory(VCSRepositoryInterface $repository, string $command): bool
	{
		$directory = $this->getRepositoryDirectory($repository);

		$previousDirectory = $this->commandExecutor->getWorkingDirectory();

		$this->commandExecutor->setWorkingDirectory($directory);
		$success = $this->commandExecutor->execute($command);

		$this->commandExecutor->setWorkingDirectory($previousDirectory);

		return $success;
	}

	private function getRepositoryDirectory(VCSRepositoryInterface $repository): string
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