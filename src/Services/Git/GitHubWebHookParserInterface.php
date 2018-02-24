<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/02/18
 * Time: 18:44
 */

namespace App\Services\Git;

use App\Exceptions\InvalidPayloadException;

interface GitHubWebHookParserInterface
{
    /**
     * Sets the payload for the parser.
     * If the payload is invalid it throws an InvalidPayloadException
     *
     * @param string $payload
     *
     * @return void
     *
     * @throws InvalidPayloadException
     */
    public function setPayload(string $payload);

    /**
     * Gets the id of the revision that has been pushed
     *
     * @return string|null
     */
    public function getCommitId();

    /**
     * Gets the url to clone the repository
     *
     * @return string|null
     */
    public function getCloneUrl();

    /**
     * Checks if the signature that was provided with the request is valid
     *
     * @param string $key
     * @param string $signature
     *
     * @return bool
     */
    public function validateSignature(string $key, string $signature) : bool;
}