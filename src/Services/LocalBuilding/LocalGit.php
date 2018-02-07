<?php

namespace App\Services\LocalBuilding;

class LocalGit implements LocalGitInterface
{
	private $rootDir;

	public function __construct(string $rootDir)
	{
		$this->rootDir = $rootDir;
	}

	public function has(string $repoName)
	{
		return file_exists($this->getRepositoryPath($repoName));
	}

	public function clone(string $repoURL) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryPath());

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git clone $repoURL .");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function fetch() : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryPath());

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git fetch -a");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	public function checkout(string $revisionNumber) : bool
	{
		$previousDirectory = getcwd();
		chdir($this->getRepositoryPath());

		list($ouput, $exitCode) = $this->executeCommandWithOutput("git checkout $revisionNumber");

		chdir($previousDirectory);
		return $exitCode === 0;
	}

	private function executeCommandWithOutput(string $command) : array
	{
		$output = [];
		$return_var = 1;
		exec($command, $output, $return_var);
	}

	private function getRepositoryPath(string $repositoryName) : string
	{
		return $this->rootDir . DIRECTORY_SEPARATOR . base64encode($repoName);
	}
}