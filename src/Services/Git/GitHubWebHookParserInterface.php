<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/02/18
 * Time: 18:44
 */

namespace App\Services\Git;

interface GitHubWebHookParserInterface
{
    public function setPayload(string $payload);

    public function getCommitId();

    public function getCloneUrl();

    public function validateSigniture(string $signiture): bool;
}