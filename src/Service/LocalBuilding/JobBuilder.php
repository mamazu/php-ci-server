<?php

namespace App\Service\LocalBuilding;

use App\Entity\BuildJobInterface;
use App\Entity\BuildState;
use App\Entity\BuildStateInterface;
use App\Entity\LogFile;
use App\Entity\VCSRepositoryInterface;
use App\Factory\BuildStateFactory;
use Doctrine\ORM\EntityManagerInterface;


class JobBuilder implements JobBuilderInterface
{
	/** @var LocalGitInterface */
	private $gitInterface;

	/** @var EntityManagerInterface */
	private $entityManager;


	public function __construct(
		LocalGitInterface $gitInterface,
		EntityManagerInterface $entityManager
	) {
		$this->gitInterface  = $gitInterface;
		$this->entityManager = $entityManager;
	}

	/** {@inheritdoc} */
	public function build(BuildJobInterface $buildJob): bool
	{
		// SETUP
		$buildJob->setStateFromString(BuildStateInterface::STATUS_INPROGRESS);
		$this->prepareSourceCode($buildJob->getRepository());

		// EXECUTE
		$logContent = $this->executeCode($buildJob);
		$buildJob->setStateFromString(BuildStateInterface::STATUS_DONE);

		// Log the result
		$logFile = $buildJob->getLogFile();
		if ($logFile === null) {
			$buildJob->addLogFile(new LogFile($logContent));
		} else {
			$logFile->append($logContent);
		}

		// Save to database
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
		$output = 'Starting buildjob ' . $buildJob->getId();
		echo $output;
		return $output;
	}
}