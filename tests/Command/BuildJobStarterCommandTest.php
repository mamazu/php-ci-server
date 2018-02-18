<?php

namespace App\Tests\Command;

use App\Command\BuildJobStarterCommand;
use App\Repository\BuildJobRepository;
use App\Repository\BuildJobRepositoryInterface;
use App\Service\LocalBuilding\JobBuilderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class BuildJobStarterCommandTest extends WebTestCase
{
	/** @var KernelInterface */
	private $bootedKernel;

	public function testExecute()
	{
		$this->bootedKernel = self::bootKernel();
		$application = new Application($this->bootedKernel);

		$application->add($this->createBuildJobCommand());

		$command = $application->find('ci:build');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$output = $commandTester->getDisplay();
		self::assertNotEmpty($output);
	}

	private function createBuildJobCommand(): BuildJobStarterCommand
	{
		$buildJobRepository = self::createMock(BuildJobRepositoryInterface::class);
		$jobBuilder = self::createMock(JobBuilderInterface::class);
		$entityManager = self::createMock(EntityManagerInterface::class);

		return new BuildJobStarterCommand($buildJobRepository, $jobBuilder, $entityManager);
	}
}