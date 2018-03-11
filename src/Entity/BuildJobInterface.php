<?php

namespace App\Entity;

interface BuildJobInterface
{
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

	/**
	 * @return bool
	 */
	public function isDone(): bool;

	/**
	 * @return bool
	 */
	public function isCanceled(): bool;
}