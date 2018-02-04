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
use App\Services\JobBuilderInterface;

class BuildJobStarterCommand extends Command
{
	/** @var BuildJobRepository */
	private $buildJobRepository;

	/** @var JobBuilderInterface */
	private $jobBuilder;

	public function __construct(
		BuildJobRepository $buildJobRepository,
		JobBuilderInterface $jobBuilder
	) {
		parent::__construct(null);
		$this->buildJobRepository = $buildJobRepository;
		$this->jobBuilder = $jobBuilder;
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
		} catch (EntityNotFoundException $e) {
			$output->writeln('Could not find build job to execute.');
		}

	}

	private function getBuildJob($identifier) : BuildJob
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
