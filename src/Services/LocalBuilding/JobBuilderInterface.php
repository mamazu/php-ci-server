<?php 

namespace App\Services\LocalBuilding;

use App\Entity\BuildJobInterface;


interface JobBuilderInterface
{
	public function build(BuildJobInterface $buildJob) : bool;
}