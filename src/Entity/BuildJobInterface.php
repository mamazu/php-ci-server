<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

interface BuildJobInterface
{
	const STATUS_PENDING = 'pending';
	const STATUS_INPROGRESS = 'in-progress';
	const STATUS_DONE = 'done';

	public function getId();

	public function getBuildJobRepository() : VCSRepositoryInterface;

	public function getState() : BuildState;
}