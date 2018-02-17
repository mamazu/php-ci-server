<?php

declare (strict_types = 1);

namespace App\Service\LocalBuilding;

class CommandExecutor implements CommandExecutorInterface
{
	private $lastExitCode = 0;
	private $lastOutput = [];
	private $empty = true;

	/** {@inheritdoc} */
	public function execute(string $command) : bool
	{
		$this->empty = false;
		$this->lastOutput = [];
		$this->lastExitCode = 1;
		exec($command . ' 2>&1', $this->lastOutput, $this->lastExitCode);

		return $this->lastExitCode === 0;
	}

	/** {@inheritdoc} */
	public function savelyExecute(string $command, array $arguments) : bool
	{
		setlocale(LC_CTYPE, "en_US.UTF-8");

		$escapedArguments = array_map('escapeshellarg', $arguments);
		$commandToExecute = $command . ' ' . join(' ', $escapedArguments);

		return $this->execute($commandToExecute);
	}

	/** {@inheritdoc} */
	public function getLastExitCode() : int
	{
		$this->checkIfFirstCommand();

		return $this->lastExitCode;
	}

	/** {@inheritdoc} */
	public function getLastOutput() : array
	{
		$this->checkIfFirstCommand();

		return $this->lastOutput;
	}

	private function checkIfFirstCommand()
	{
		if ($this->empty) {
			throw new \Exception('Execute a command first');
		}
	}
}