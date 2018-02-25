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

	/**
	 * @param int $page
	 * @param int $pageSize
	 *
	 * @return BuildJobInterface[]
	 */
	public function getPaged(int $page, int $pageSize): array;

	/**
	 * Returns an associative array with the key being the state and the value being the number of build jobs in this state
	 *
	 * @return array
	 */
	public function getStateCount(): array;
}