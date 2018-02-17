<?php
namespace App\Tests\Services\LocalBuilding;

use PHPUnit\Framework\TestCase;
use App\Services\LocalBuilding\CommandExecutor;
use App\Services\LocalBuilding\CommandExecutorInterface;
use Exception;

class CommandExecutorTest extends TestCase
{
	/** @var CommandExecutorInterface */
	private $commandExecutor;

	public function setUp()
	{
		$this->commandExecutor = new CommandExecutor();
	}

	public function testUnitialiasedExitCode()
	{
		self::expectException(Exception::class);
		self::expectExceptionMessage('Execute a command first');

		$this->commandExecutor->getLastExitCode();
	}

	public function testUnitialiasedOutput()
	{
		self::expectException(Exception::class);
		self::expectExceptionMessage('Execute a command first');

		$this->commandExecutor->getLastOutput();
	}

	public function testUnescapedCommand()
	{
		// Execute
		$success = $this->commandExecutor->execute('echo "Hello";');

		// Asser
		self::assertTrue($success);
		self::assertEquals(0, $this->commandExecutor->getLastExitCode());

		$output = $this->commandExecutor->getLastOutput();
		self::assertEquals(1, count($output));
		self::assertEquals('Hello', $output[0]);
	}

	public function testUnescapedCommandWithError()
	{
		// Execute
		$success = $this->commandExecutor->execute('some_command_that_does_not_exist');

		// Assert
		self::assertFalse($success);
		self::assertNotEquals(0, $this->commandExecutor->getLastExitCode());

		$output = $this->commandExecutor->getLastOutput();
		self::assertEquals(1, count($output));
		self::assertContains('not found', $output[0]);

	}

	public function testEscapedCommand()
	{
		// Execute
		$success = $this->commandExecutor->execute('echo', ['Hallo|echo']);

		// Assert
		self::assertTrue($success);
		self::assertEquals(0, $this->commandExecutor->getLastExitCode());

		$output = $this->commandExecutor->getLastOutput();
		self::assertEquals(1, count($output));
		self::assertContains('', $output[0]);
	}
}