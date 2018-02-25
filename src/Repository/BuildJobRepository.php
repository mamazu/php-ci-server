<?php

namespace App\Repository;

use App\Entity\BuildJob;
use App\Entity\BuildJobInterface;
use App\Entity\BuildStateInterface;
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
			return $buildJob->getState()->getName() === BuildStateInterface::STATUS_PENDING;
		});
		return count($pendingJobs) > 0 ? reset($pendingJobs) : null;
	}

	public function getPaged(int $page, int $pageSize): array
	{
		$queryBuilder = $this->createQueryBuilder('build_job')
			->setFirstResult($page*$pageSize)
			->setMaxResults($pageSize);

		return $queryBuilder->getQuery()->getResult();
	}

	public function getStateCount(): array
	{
		$summary = [];
		foreach ($this->findAll() as $buildJob) {
			/** @var BuildJobInterface $buildJob */
			$state = $buildJob->getState()->getName();
			if (!isset($summary[$state])) {
				$summary[$state] = 0;
			}
			$summary[$state]++;
		}
		return $summary;
	}
}
