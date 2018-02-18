<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 17/02/18
 * Time: 23:35
 */

namespace App\Repository;


use App\Entity\BuildJobInterface;

interface BuildJobRepositoryInterface
{
	/**
	 * @return BuildJobInterface|null
	 */
	public function getNextBuildJob();
}