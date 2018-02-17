<?php

namespace App\Services\LocalBuilding;

interface LocalGitInterface
{
	public function clone() : bool;

	public function fetch() : bool;

	public function checkout() : bool;
}