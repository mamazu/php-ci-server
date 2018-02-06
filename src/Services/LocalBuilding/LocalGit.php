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
		$this->rootDir . DIRECTORY_SEPARATOR . base64encode($repoName);
	}

	public function clone(string $repoURL) : bool
	{
		return false;
	}

	public function fetch() : bool
	{
		return false;
	}

	public function checkout(string $revisionNumber) : bool
	{
		return false;
	}
}