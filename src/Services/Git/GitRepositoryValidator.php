<?php

declare (strict_types = 1);

namespace App\Services\Git;

use App\Exception \{
	InvalidRepositoryURLException, InvalidRevisionException
};
use App\Services\VCSRepositoryValidatorInterface;


class GitRepositoryValidator implements VCSRepositoryValidatorInterface
{
	/** {@inheritdoc} */
	public function validateRepositoryURL(string $url)
	{
		if (is_int(strpos($url, '@'))) {
			// Regex for ssh repos
			$regExp = '/^[^@]+@[^:]+:[^\/]+\/[^\/]+.git$/';
		} else {
			// Regex for http repos
			$regExp = '/^http(s)?:\/\/[^\/]+\/[^\/]+\/[^\/]+.git$/';
		}

		$matches = preg_match($regExp, $url);
		if ($matches !== 1) {
			throw new InvalidRepositoryURLException($url);
		}
	}

	/** {@inheritdoc} */
	public function validateRevisionNumber(string $revisionNumber)
	{
		$lengthMatches = strlen($revisionNumber) === 40;
		$isAlphaNumerical = preg_match('/^[a-z0-9]+$/', $revisionNumber) === 1;

		if (!$lengthMatches || !$isAlphaNumerical) {
			throw new InvalidRevisionException($revisionNumber);
		}
	}
}