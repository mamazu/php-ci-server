<?php

namespace App\Entity;

interface BuildJobInterface
{
	const STATUS_PENDING    = 'pending';
	const STATUS_INPROGRESS = 'progress';
	const STATUS_DONE       = 'done';

	public function getId();

	public function getRepository(): VCSRepositoryInterface;

	public function getState(): BuildStateInterface;

	/**
	 * All build states the job went through
	 *
	 * @return array
	 */
	public function getStates(): array;
}