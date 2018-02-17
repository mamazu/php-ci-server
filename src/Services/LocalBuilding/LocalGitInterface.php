<?php

namespace App\Services\LocalBuilding;

use App\Entity\VCSRepositoryInterface;


interface LocalGitInterface
{
	public function clone(VCSRepositoryInterface $repository) : bool;

	public function fetch(VCSRepositoryInterface $repository) : bool;

	public function checkout(VCSRepositoryInterface $repository) : bool;
}