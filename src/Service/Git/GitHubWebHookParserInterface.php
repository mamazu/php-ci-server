<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/02/18
 * Time: 18:44
 */

namespace App\Service\Git;

use App\Entity\VCSRepositoryInterface;

interface GitHubWebHookParserInterface
{
	/**
	 * Sets the payload for the parser.
	 * If the payload is invalid it throws an InvalidPayloadException
	 *
	 * @param string $payload
	 *
	 * @return VCSRepositoryInterface
	 *
	 */
    public function getRepository(string $payload): VCSRepositoryInterface;

    /**
     * Validates the signature Github sends with the request.
     *
     * @param string $signature
     * @return boolean
     */
    public function validateSignature(string $signature) : bool;
}