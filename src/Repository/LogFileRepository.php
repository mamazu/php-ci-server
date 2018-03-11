<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/03/18
 * Time: 14:52
 */

namespace App\Repository;


use App\Entity\LogFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LogFileRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, LogFile::class);
	}

	public function getLogFileByBuildJobId(int $id): ?LogFile
	{
		$buildJob = $this->_em->getRepository('App:BuildJob')->findOneBy(['id' => $id]);
		if ($buildJob === null) {
			return null;
		}
		return $buildJob->getLogFile();
	}
}