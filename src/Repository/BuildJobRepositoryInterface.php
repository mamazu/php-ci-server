<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 17/02/18
 * Time: 23:35
 */

namespace App\Repository;


use App\Entity\BuildJobInterface;
use Doctrine\Common\Persistence\ObjectRepository;

interface BuildJobRepositoryInterface extends ObjectRepository
{
	/**
	 * @return BuildJobInterface|null
	 */
	public function getNextBuildJob();
}