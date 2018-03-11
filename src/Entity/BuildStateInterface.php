<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface BuildStateInterface
{
	const STATUS_PENDING    = 'pending';
	const STATUS_INPROGRESS = 'progress';
	const STATUS_DONE       = 'done';
	const STATUS_CANCELED   = 'canceled';

	/**
	 * @return int|null
	 */
	public function getId();

	/**
	 * @param BuildJobInterface $buildJob
	 */
	public function setBuildJob(BuildJobInterface $buildJob);

	/**
	 * @return BuildJobInterface
	 */
	public function getBuildJob(): BuildJobInterface;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return DateTime
	 */
	public function getTime(): DateTime;

	/**
	 * Gets all possible state strings
	 *
	 * @return array
	 */
	public function getAllStates(): array;
}