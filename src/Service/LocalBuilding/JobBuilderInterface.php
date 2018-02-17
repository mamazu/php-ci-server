<?php 

namespace App\Service\LocalBuilding;

use App\Entity\BuildJobInterface;


interface JobBuilderInterface
{
	/**
	 * Builds a buildjob and returns if there were any errors
	 *
	 * @param BuildJobInterface $buildJob
	 * @return boolean
	 */
	public function build(BuildJobInterface $buildJob) : bool;
}