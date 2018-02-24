<?php

namespace App\Controller;

use App\Entity\BuildJobInterface;
use App\Repository\BuildJobRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

	private $buildJobRepository;

	public function __construct(BuildJobRepositoryInterface $buildJobRepository)
	{
		$this->buildJobRepository = $buildJobRepository;
	}

	public function index(): Response
	{
		$allJobs = $this->buildJobRepository->findAll();

		$summary = [];
		foreach ($allJobs as $buildJob) {
			/** @var BuildJobInterface $buildJob */
			$state = $buildJob->getState()->getName();
			if (!isset($summary[$state])) {
				$summary[$state] = 0;
			}
			$summary[$state]++;
		}

		return $this->render('index.html.twig', [
			'summary'    => $summary,
			'build_jobs' => $allJobs
		]);
	}
}
