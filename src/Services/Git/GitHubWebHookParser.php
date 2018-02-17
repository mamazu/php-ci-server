<?php

declare (strict_types=1);

namespace App\Services\Git;

use App\Exceptions\InvalidPayloadException;
use stdClass;

class GitHubWebHookParser implements GitHubWebHookParserInterface
{
    /** @var stdClass $data*/
    private $data;

    /** @var string $key */
    private $key;

    /** {@inheritdoc} */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /** {@inheritdoc} */
    public function setPayload(string $payload)
    {
        if ($payload === null) return;

        $payloadObject = json_decode($payload);

        if ($payloadObject === null) {
            throw new InvalidPayloadException('The payload is not valid json');
        }

        $this->data = $payloadObject;
    }

    /** {@inheritdoc} */
    private function getProperty($object, string $property)
    {
        if (property_exists($object, $property)) {
            return $object->$property;
        }
        return null;
    }

    /** {@inheritdoc} */
    public function getCommitId()
    {
        $commit = $this->getProperty($this->data, 'head_commit');
        return is_null($commit) ? null : $this->getProperty($commit, 'id');
    }

    /** {@inheritdoc} */
    public function getCloneUrl()
    {
        $repo = $this->getProperty($this->data, 'repository');
        return is_null($repo) ? null : $this->getProperty($repo, 'clone_url');
    }

    /** {@inheritdoc} */
    public function validateSignature(string $signature): bool
    {
        $rawData           = file_get_contents('php://input');
        $expectedSignature = 'sha1=' . hash_hmac('sha1', $rawData, $this->key);
        return $signature === $expectedSignature;
    }
}

?>
