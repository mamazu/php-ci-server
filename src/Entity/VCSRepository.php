<?php
declare (strict_types = 1);

namespace App\Entity;

use App\Service\VCSRepositoryValidatorInterface;

use App\Exception\{InvalidRepositoryURLException, InvalidRevisionException};

class VCSRepository implements VCSRepositoryInterface
{
	/** @var string */
	private $cloneURL;

	/** @var string */
	private $name;

	/** @var string */
	private $revision;

    /**
     * VCSRepository constructor.
     *
     * @param VCSRepositoryValidatorInterface $validator
     * @param string                          $cloneURL
     * @param string                          $repositoryName
     * @param string                          $revision
     *
     * @throws InvalidRepositoryURLException
     * @throws InvalidRevisionException
     */
	public function __construct(
		VCSRepositoryValidatorInterface $validator,
		string $cloneURL,
		string $repositoryName,
		string $revision
	) {
		$validator->validateRepositoryURL($cloneURL);
		$validator->validateRevisionNumber($revision);

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