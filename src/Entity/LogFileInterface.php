<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/03/18
 * Time: 14:10
 */

namespace App\Entity;

use DateTimeImmutable;

interface LogFileInterface
{
	/**
	 * @return int
	 */
	public function getId(): int;

	/**
	 * @return string
	 */
	public function getContent(): string;

	/**
	 * @param string $content
	 */
	public function setContent(string $content): void;

	/**
	 * @return DateTime
	 */
	public function getCreatedAt(): DateTimeImmutable;

	/**
	 * @return BuildJobInterface
	 */
	public function getBuildJob(): BuildJobInterface;
}