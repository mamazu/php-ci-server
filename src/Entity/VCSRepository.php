<?php
declare (strict_types = 1);

namespace App\Entity;

use App\Services\VCSRepositoryValidatorInterface;

class VCSRepository implements VCSRepositoryInterface
{
	/** @var string */
	private $cloneURL;

	/** @var string */
	private $name;

	/** @var string */
	private $revision;

	public function __construct(
		VCSRepositoryValidatorInterface $validator,
		string $cloneURL,
		string $repositoryName,
		string $revision
	) {
		$this->validator->validateRepositoryURL($cloneURL);
		$this->validator->validateRevisionNumber($revision);

		$this->cloneURL = $cloneURL;
		$this->name = $repositoryName;
		$this->revision = $revision;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getRevisionNumber() : string
	{
		return $this->revision;
	}

	public function getCloneURL() : string
	{
		return $this->cloneURL;
	}
}