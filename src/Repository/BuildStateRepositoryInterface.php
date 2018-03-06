<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 03/03/18
 * Time: 15:19
 */

namespace App\Repository;

interface BuildStateRepositoryInterface
{
	public function getSummary(): array;
}