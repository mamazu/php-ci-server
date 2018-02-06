<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Repository\BuildJobRepository;
use Exception;
use Doctrine\ORM\EntityNotFoundException;
use App\Entity\BuildJob;
use App\Entity\BuildJobInterface;
use App\Services\LocalBuilding\JobBuilderInterface;
use Doctrine\ORM\EntityManagerInterface;

class BuildJobStarterCommand extends Command
{
	/** @var BuildJobRepository */
	private $buildJobRepository;

	/** @var JobBuilderInterface */
	private $jobBuilder;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		BuildJobRepository $buildJobRepository,
		JobBuilderInterface $jobBuilder,
		EntityManagerInterface $entityManager
	) {
		parent::__construct(null);
		$this->buildJobRepository = $buildJobRepository;
		$this->jobBuilder = $jobBuilder;
		$this->entityManager = $entityManager;
	}

	protected function configure()
	{
		$this
			->setName('ci:build')
			->setDescription('Builds the next job in the build queue')
			->setHelp('Builds the next CI Job. This can be automated with a cron job')
			->addArgument('jobOrCommitId', InputArgument::OPTIONAL, 'Id of the job. If empty it takes the next', null);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {
			$buildJob = $this->getBuildJob($input->getArgument('jobOrCommitId'));
			if ($buildJob === null) {
				$output->writeln('info', 'The ci is empty');
			} else {
				$buildJob->setState(BuildJobInterface::STATUS_INPROGRESS);
				$this->entityManager->flush();
				$this->jobBuilder->build($buildJob);
			}
		} catch (EntityNotFoundException $e) {
			$output->writeln('Could not find build job to execute.');
		}
	}

	/**
	 * Gets the next build job to execute
	 * 
	 * @param $identifier
	 * 		If the $identifier is null, it will get the next build job based on the build job queue
	 * 		If the $identifier is an integer then it will find the build job with the id and build it
	 * 		otherwise it will look for a commit id with the content of the parameter
	 * 
	 * @return BuildJob|null 
	 */
	private function getBuildJob($identifier = null)
	{
		if ($identifier === null) {
			return $this->buildJobRepository->getNextBuildJob();
		}
		if (is_int($identifier)) {
			return $this->buildJobRepository->find($identifier);
		}

		return $this->buildJobRepository->findByCommitId($identifier);
	}

}
