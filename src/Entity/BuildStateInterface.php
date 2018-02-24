<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface BuildStateInterface
{
	public function getId();

	public function setBuildJob(BuildJobInterface $buildJob);

	public function getBuildJob(): BuildJobInterface;

	public function getName(): string;

	public function getTime(): DateTime;
}