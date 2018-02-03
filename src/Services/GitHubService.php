<?php

declare (strict_types = 1);

namespace App\Services;

class GitHubService
{
	/** @var stdClass $this->data */
	private $data;

	/** @var string $key */
	private $key;

	public function __construct(string $key)
	{
		$this->key = $key;
	}

	public function setPayload(string $payload)
	{
		$this->data = json_decode($payload);
	}

	private function getProperty($object, string $property)
	{
		if (property_exists($object, $property)) {
			return $object->$property;
		}
		return null;
	}

	public function getCommitId()
	{
		$commit = $this->getProperty($this->data, 'head_commit');
		return is_null($commit) ? null : $this->getProperty($commit, 'id');
	}

	public function getCloneUrl()
	{
		$repo = $this->getProperty($this->data, 'repository');
		return is_null($repo) ? null : $this->getProperty($repo, 'clone_url');
	}

	public function validateSigniture(string $signiture) : bool
	{
		$rawData = file_get_contents('php://input');
		$expectedSigniture = 'sha1=' . hash_hmac('sha1', $rawData, $this->key);
		return $signiture === $expectedSigniture;
	}
}
?>
