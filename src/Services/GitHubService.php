<?php

declare (strict_types = 1);

namespace App\Services;

class GitHubService
{
	/** @var stdClass $this->data */
	private $data;

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
}
?>
