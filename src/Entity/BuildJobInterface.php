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

	/**
	 * @return string
	 */
	public function getCreator(): string;

	/**
	 * @param string $creator
	 */
	public function setCreator(string $creator): void;

	/**
	 * @return LogFile|null
	 */
	public function getLogFile(): ?LogFile;

	/**
	 * @param LogFile $logFile
	 *
	 * @return string
	 */
	public function addLogFile(LogFile $logFile): void;

	/**
	 * @param string $statusString
	 */
	public function setStateFromString(string $statusString): void;
}