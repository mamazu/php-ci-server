<?php

declare (strict_types = 1);

namespace App\Service\General;

use App\Service\VCSRepositoryValidatorInterface;

class VCSValidatorAccumulator implements VCSValidatorAccumulatorInterface
{
	private $validators = [];

	public function set(string $type, VCSRepositoryValidatorInterface $validator) : void
	{
		$this->validators[$type] = $validator;
	}

	public function get(string $type) : ? VCSRepositoryValidatorInterface
	{
		if (isset($this->validators[$type])) {
			return $this->validators[$type];
		}

		return null;
	}
}