<?php

namespace App\Tests\Command;

use App\Command\BuildJobStarterCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BuildJobStarterCommandTest extends KernelTestCase
{
	public function testExecute()
	{
		$kernel = self::bootKernel();
		$application = new Application($kernel);

		$application->add(new BuildJobStarterCommand());

		$command = $application->find('ci:build-next');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['command' => $command->getName()]);

		$output = $commandTester->getDisplay();
		self::assertNotEmpty($output);
	}
}