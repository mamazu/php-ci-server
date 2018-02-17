<?php

namespace App\Services\LocalBuilding;

use App\Entity\VCSRepositoryInterface;
use Symfony\Component\Filesystem\Filesystem;

class LocalGit implements LocalGitInterface
{
	/** @var string */
	private $rootDir;

	public function __construct(string $rootDir)
	{
		$this->rootDir = $rootDir;
	}

	public function has(VCSRepositoryInterface $repository) : bool
	{
		return file_exists($this->getRepositoryDirectory($repository));
	}

	public function createRepositoryDirectory(VCSRepositoryInterface $repository)
	{
		$fileSystem = new Filesystem();
		$fileSystem->mkdir($this->getRepositoryDirectory($repository));
	}

	public function clone(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory($repository));
		$repositoryURL = $repository->getCloneURL();

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git clone $repositoryURL .");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function fetch(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory($repository));

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git fetch -a");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function checkout(VCSRepositoryInterface $repository) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryDirectory($repository));
		$revisionNumber = $repository->getRevisionNumber();

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