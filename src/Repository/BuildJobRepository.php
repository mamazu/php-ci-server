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
        $statement = $this->_em->getConnection()->prepare('
        SELECT * 
        FROM (
            SELECT
                MAX(id) AS id,
                build_job_id,
                `name` AS stateName
            FROM
                `build_state`
            GROUP BY
                build_job_id
        ) state
        WHERE stateName = :stateName
        LIMIT 1;
        ');
        $statement->execute([':stateName' => 'pending']);
        $result = $statement->fetchAll();
        return count($result) > 0 ? $this->find($result[0]['build_job_id']) : null;
    }
}
