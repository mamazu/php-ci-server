<?php

declare (strict_types=1);

namespace App\Services\Git;

use App\Exceptions\InvalidRepositoryURLException;
use App\Exceptions\InvalidRevisionException;
use App\Services\VCSRepositoryValidator;


class GitRepositoryValidator implements VCSRepositoryValidator
{
    /** {@inheritdoc} */
    public function validateRepositoryURL(string $url)
    {
        $gitHubURL = 'github.com';
        if (is_int(strpos($url, '@'))) {
            // Regex for ssh repos
            $gitUser = 'git';

            $regExp = '/^' . $gitUser . '@' . $gitHubURL . '+:[^\/]+\/[^\/]+.git$/';
        } else {
            // Regex for http repos
            $regExp = '/^http(s)?:\/\/' . $gitHubURL . '\/[^\/]+\/[^\/]+.git$/';
        }

        $matches = preg_match($regExp, $url);
        if ($matches !== 1) {
            throw new InvalidRepositoryURLException($url);
        }
    }

    /** {@inheritdoc} */
    public function validateRevisionNumber(string $revisionNumber)
    {
        $lengthMatches    = strlen($revisionNumber) === 40;
        $isAlphaNumerical = preg_match('/^[a-z0-9]+$/', $revisionNumber) === 1;

        if (!$lengthMatches || !$isAlphaNumerical) {
            throw new InvalidRevisionException($revisionNumber);
        }
    }
}