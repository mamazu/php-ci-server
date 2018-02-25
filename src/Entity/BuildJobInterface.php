<?php

namespace App\Entity;

interface BuildJobInterface
{
	const STATUS_PENDING    = 'pending';
	const STATUS_INPROGRESS = 'progress';
	const STATUS_DONE       = 'done';

	/**
	 * @return int|null
	 */
	public function getId(): ?int;

	/**
	 * @return VCSRepositoryInterface
	 */
	public function getRepository(): VCSRepositoryInterface;

	/**
	 * @return BuildStateInterface
	 */
	public function getState(): BuildStateInterface;

	/**
	 * All build states the job went through
	 *
	 * @return array
	 */
	public function getStates(): array;

	/**
	 * @param BuildStateInterface $state
	 */
	public function setState(BuildStateInterface $state): void;
}