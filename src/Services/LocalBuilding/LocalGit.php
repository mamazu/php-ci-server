<?php

namespace App\Services\LocalBuilding;

use App\Entity\VCSRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;

class LocalGit implements LocalGitInterface
{
	/** @var string */
	private $rootDir;

	/** @var VSCRepositoryInterface */
	private $repository;

	public function __construct(string $rootDir, VCSRepositoryInterface $repository)
	{
		$this->rootDir = $rootDir;
		$this->repository = $repository;
	}

	public function has() : bool
	{
		return file_exists($this->getRepositoryDirectory());
	}

	public function createRepositoryDirectory()
	{
		$fileSystem = new Filesystem();
		$fileSystem->mkdir($this->getRepositoryDirectory());
	}

	public function clone() : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory());
		$repositoryURL = $this->repository->getCloneURL();

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git clone $repositoryURL .");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function fetch() : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory());

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git fetch -a");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function checkout() : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory());
		$revisionNumber = $this->repository->getRevisionNumber();

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git checkout $revisionNumber");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	private function executeCommandWithOutput(string $command) : array
	{
		$output = [];
		$exitCode = 1;
		exec($command, $output, $exitCode);

		return [$output, $exitCode];
	}

	private function getRepositoryDirectory() : string
	{
		$repositoryName = $this->repository->getName();

		if (strlen($repositoryName) === 0) {
			$directory = '_empty';
		} else {
			$directory = base64_encode($repositoryName);
		}

		return $this->rootDir . DIRECTORY_SEPARATOR . $directory;
	}
}