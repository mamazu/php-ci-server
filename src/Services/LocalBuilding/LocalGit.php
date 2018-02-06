<?php

namespace App\Services\LocalBuilding;

class LocalGit implements LocalGitInterface
{
	public function __construct(string $rootDir)
	{

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