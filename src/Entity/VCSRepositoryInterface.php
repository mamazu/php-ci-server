<?php
declare (strict_types = 1);

namespace App\Entity;

interface VCSRepositoryInterface
{
	/**
	 * @return string
	 */
	public function getName() : string;

	/**
	 * @return string
	 */
	public function getRevisionNumber() : string;

	/**
	 * @return string
	 */
	public function getCloneURL() : string;
}