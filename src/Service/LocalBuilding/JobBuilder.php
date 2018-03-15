<?php

namespace App\Service\LocalBuilding;

use App\Entity\BuildJobInterface;
use App\Entity\BuildState;
use App\Entity\BuildStateInterface;
use App\Entity\LogFile;
use App\Entity\VCSRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;


class JobBuilder implements JobBuilderInterface
{
	/** @var LocalGitInterface */
	private $gitInterface;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(LocalGitInterface $gitInterface, EntityManagerInterface $entityManager)
	{
		$this->gitInterface  = $gitInterface;
		$this->entityManager = $entityManager;
	}

	/** {@inheritdoc} */
	public function build(BuildJobInterface $buildJob): bool
	{
		$this->prepareSourceCode($buildJob->getRepository());
		$buildJob->setState(new BuildState($buildJob, BuildStateInterface::STATUS_INPROGRESS));

		$logContent = $this->executeCode($buildJob);
		$logFile    = $buildJob->getLogFile();
		if ($logFile === null) {
			$buildJob->addLogFile(new LogFile($logContent));
		} else {
			$logFile->append($logContent);
		}

		$this->entityManager->flush();
		return true;
	}

	/**
	 * Prepares the sourcecode (aka. checkout and update)
	 *
	 * @param VCSRepositoryInterface $repository
	 *
	 * @return void
	 */
	private function prepareSourceCode(VCSRepositoryInterface $repository): void
	{
		if ($this->gitInterface->has($repository)) {
			$this->gitInterface->fetch($repository);
		} else {
			$this->gitInterface->clone($repository);
		}

		$this->gitInterface->checkout($repository);
	}

	private function executeCode(BuildJobInterface $buildJob): string
	{
		return '';
	}
}