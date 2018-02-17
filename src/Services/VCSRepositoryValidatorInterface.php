<?php
declare (strict_types = 1);

namespace App\Services;

interface VCSRepositoryValidatorInterface
{
	/**
	 * Validates a given url and checks if this a repository. If it is not valid, it throws an error.
	 * 
	 * @param string $url URL to validate
	 * 
	 * @throws GitValidationException
	 */
	public function validateRepositoryURL(string $url);

	/**
	 * Validates a given revision number and checks if this a valid revision. If it is not valid, it throws an error.
	 * 
	 * @param string $revisionNumber revision number to validate
	 * 
	 * @throws InvalidRevisionException
	 */
	public function validateRevisionNumber(string $revisionNumber);
}