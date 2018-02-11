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

    public function getCommitId();

    public function getCloneUrl();

    public function validateSigniture(string $signature): bool;
}