<?php

namespace App\Services\LocalBuilding;

interface LocalGitInterface
{
	public function clone(string $repositoryURL) : bool;

	public function fetch() : bool;

	public function checkout(string $revisionNumber) : bool;
}