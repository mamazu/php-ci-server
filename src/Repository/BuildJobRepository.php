<?php

namespace App\Repository;

use App\Entity\BuildJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityNotFoundException;

class BuildJobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BuildJob::class);
    }

    public function getNextBuildJob()
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->innerJoin('b.states', 's')
            ->orderBy('s.time', 'ASC')
            ->andWhere('s.name = :pending')
            ->setParameter('pending', 'pending')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
