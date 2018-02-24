<?php

declare (strict_types = 1);

namespace App\Services\Git;

use App\Exceptions\InvalidPayloadException;
use App\Services\PHPStreamsInterface;
use stdClass;

class GitHubWebHookParser implements GitHubWebHookParserInterface
{
    /** @var stdClass $data*/
    private $data;

    /** @var PHPStreamsInterface */
    private $streams;

    public function __construct(PHPStreamsInterface $streams)
    {
        $this->streams = $streams;
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
    public function validateSignature(string $key, string $signature) : bool
    {
        $rawData = $this->streams->getInput();
        $expectedSignature = 'sha1=' . hash_hmac('sha1', $rawData, $key);

        return $signature === $expectedSignature;
    }
}

?>
