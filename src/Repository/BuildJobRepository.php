<?php

namespace App\Repository;

use App\Entity\BuildJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BuildJobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BuildJob::class);
    }

    public function getSummary() : array
    {
        $result = $this->createQueryBuilder('b')
            ->select('COUNT(b.id) as num')
            ->addSelect('b.status')
            ->addGroupBy('b.status')
            ->getQuery()
            ->getResult();

        $associative = [];
        foreach ($result as $row) {
            $associative[$row['status']] = intval($row['num']);
        }
        return $associative;
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('b')
            ->where('b.something = :value')->setParameter('value', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */
}
