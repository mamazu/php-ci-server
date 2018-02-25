<?php

namespace App\Repository;

use App\Entity\BuildJob;
use App\Entity\BuildJobInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityNotFoundException;

class BuildJobRepository extends ServiceEntityRepository implements BuildJobRepositoryInterface
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, BuildJob::class);
	}

	/** {@inheritdoc} */
	public function getNextBuildJob()
	{
		$allBuildJobs = $this->findAll();
		$pendingJobs  = array_filter($allBuildJobs, function (BuildJobInterface $buildJob) {
			return $buildJob->getState()->getName() === BuildJobInterface::STATUS_PENDING;
		});
		return count($pendingJobs) > 0 ? reset($pendingJobs) : null;
	}
}
