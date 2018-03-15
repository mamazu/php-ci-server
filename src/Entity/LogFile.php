<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/03/18
 * Time: 14:08
 */

namespace App\Entity;


use DateTimeImmutable;
use Exception;

class LogFile implements LogFileInterface
{
	/** @var int */
	private $id;

	/** @var resource */
	private $content;

	/** @var DateTimeImmutable */
	private $createdAt;

	/** @var BuildJobInterface */
	private $buildJob;

	public function __construct(string $content)
	{
		$this->content   = $content;
		$this->createdAt = new DateTimeImmutable('now');
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		$content = stream_get_contents($this->content);
		if (is_bool($content)) {
			throw new Exception('Could not get log file content');
		}

		return $content;
	}

	/**
	 * @param string $content
	 */
	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	/**
	 * @return DateTime
	 */
	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getBuildJob(): BuildJobInterface
	{
		return $this->buildJob;
	}

	public function append(string $content): void
	{
		$this->content = $this->getContent() . $content;
	}
}