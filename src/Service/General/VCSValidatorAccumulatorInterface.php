<?php

declare (strict_types = 1);
namespace App\Service\General;

use App\Service\VCSRepositoryValidatorInterface;

interface VCSValidatorAccumulatorInterface
{
	public function set(string $type, VCSRepositoryValidatorInterface $validator) : void;

	public function get(string $type) : ? VCSRepositoryValidatorInterface;
}