<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 03/03/18
 * Time: 15:16
 */

namespace App\Repository;


use App\Entity\BuildState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

class BuildStateRepository extends ServiceEntityRepository implements BuildStateRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, BuildState::class);
	}

	public function getSummary(): array
	{
		$queryString = '
SELECT * FROM 
	(SELECT build_job_id, MAX(time) AS "m_time" FROM build_state GROUP BY build_job_id) t 
RIGHT JOIN build_state 
	ON t.build_job_id = build_state.build_job_id AND t.m_time = build_state.time 
WHERE t.build_job_id IS NOT NULL;';

		try {
		$connection = $this->_em->getConnection();
			$query = $connection->query($queryString);
		$query->execute();
		$result = $query->fetchAll();
		} catch (DBALException $e) {
			return [];
		}

		$summary = [];
		foreach($result as $row){
			$state = $row['name'];
			if(isset($summary[$state])){
				$summary[$state]++;
			}else{
				$summary[$state] = 1;
			}
		}

		return $summary;
	}
}