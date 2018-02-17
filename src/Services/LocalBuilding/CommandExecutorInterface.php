<?php

declare (strict_types = 1);

namespace App\Services\LocalBuilding;

use Exception;

interface CommandExecutorInterface
{
	/**
	 * Executes a given string as a command on the console and returns whether it was
	 * successful or not
	 * 
	 * @param string $command Command to execute
	 * 
	 * @return bool
	 */
	public function execute(string $command) : bool;

	/**
	 * Executes a given command on the console, escapes the arguments and returns whether
	 * it was successful or not
	 * 
	 * @param string $command Command to execute
	 * @param array $arguments 
	 * 
	 * @return bool
	 */
	public function savelyExecute(string $command, array $arguments) : bool;

	/**
	 * Returns the exit code of the last command that runs. If there was no command
	 * run before, it thows an exception.
	 * 
	 * @return int
	 * @throws Exception
	 */
	public function getLastExitCode() : int;

	/**
	 * Returns the output of the last command as a list of strings (every element is a new line)
	 * 
	 * @return array
	 */
	public function getLastOutput() : array;
}