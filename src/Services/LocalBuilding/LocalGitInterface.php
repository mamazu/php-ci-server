<?php

namespace App\Services\LocalBuilding;

use App\Entity\VCSRepositoryInterface;
use App\Entity\VCSRepository;


interface LocalGitInterface
{
	/**
	 * Checks if the repository already has a local copy
	 *
	 * @param VCSRepositoryInterface $repository
	 * @return boolean
	 */
	public function has(VCSRepositoryInterface $repository) : bool;

	/**
	 * Clones the repository from the clone URL
	 *
	 * @param VCSRepositoryInterface $repository
	 * @return boolean
	 */
	public function clone(VCSRepositoryInterface $repository) : bool;

	/**
	 * Fetches the changes from the github server
	 *
	 * @param VCSRepositoryInterface $repository
	 * @return boolean
	 */
	public function fetch(VCSRepositoryInterface $repository) : bool;

	/**
	 * Checks the desired revision out
	 *
	 * @param VCSRepositoryInterface $repository
	 * @return boolean
	 */
	public function checkout(VCSRepositoryInterface $repository) : bool;
}