<?php

namespace App\Controller;

use App\Entity\BuildJobInterface;
use App\Repository\BuildJobRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

	/** @var BuildJobRepositoryInterface */
	private $buildJobRepository;

	public function __construct(BuildJobRepositoryInterface $buildJobRepository)
	{
		$this->buildJobRepository = $buildJobRepository;
	}

	public function index(int $page=1): Response
	{
		$page = max($page, 0);
		$allJobs = $this->buildJobRepository->getPaged($page, 100);
		$summary = $this->buildJobRepository->getStateCount();

		return $this->render('index.html.twig', [
			'summary'    => $summary,
			'build_jobs' => $allJobs,
			'route_name' => 'index',
			'page'       => $page
		]);
	}
}
