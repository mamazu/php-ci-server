<?php

declare (strict_types=1);

namespace App\Service\Git;

use App\Entity\VCSRepository;
use App\Entity\VCSRepositoryInterface;
use Exception;
use stdClass;

class GitHubWebHookParser implements GitHubWebHookParserInterface
{
	/** @var stdClass $data */
	private $data;

	/** @var string $key */
	private $key;

	/** {@inheritdoc} */
	public function __construct(string $key)
	{
		$this->key = $key;
	}

	public function getRepository(string $payload): VCSRepositoryInterface
	{
		$this->setPayload($payload);

		try {
			$validator = new GitRepositoryValidator();
			$cloneURL  = $this->getValueFromPath('repository.clone_url');
			$name      = $this->getValueFromPath('repository.name');
			$commitId  = $this->getValueFromPath('head_commit.id');

			return new VCSRepository($validator, $cloneURL, $name, $commitId);
		} catch (\TypeError $exception) {
			throw new \Exception('Invalid json. Missing information', 0, $exception);
		}
	}

	/** {@inheritdoc} */
	private function setPayload(string $payload)
	{
		$payloadObject = json_decode($payload);

		if ($payloadObject === null) {
			throw new Exception('The payload is not valid json');
		}

		$this->data = $payloadObject;
	}

	/**
	 * @param string $path
	 *
	 * @return string|null
	 */
	private function getValueFromPath(string $path)
	{
		$currentNode = $this->data;

		foreach (explode('.', $path) as $pathPart) {
			if (property_exists($currentNode, $pathPart)) {
				$currentNode = $currentNode->$pathPart;
			} else {
				return null;
			}
		}

		return !is_string($currentNode) ? (string)$currentNode : $currentNode;
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
