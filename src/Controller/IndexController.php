<?php

namespace App\Controller;

use App\Entity\BuildJobInterface;
use App\Repository\BuildJobRepositoryInterface;
use App\Repository\BuildStateRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

	/** @var BuildJobRepositoryInterface */
	private $buildJobRepository;
	/**
	 * @var BuildStateRepositoryInterface
	 */
	private $buildStateRepository;

	public function __construct(
		BuildJobRepositoryInterface $buildJobRepository,
		BuildStateRepositoryInterface $buildStateRepository
	) {
		$this->buildJobRepository   = $buildJobRepository;
		$this->buildStateRepository = $buildStateRepository;
	}

	public function index(string $page = '1'): Response
	{
		$page    = max(intval($page), 0);
		$allJobs = $this->buildJobRepository->getPaged($page, 100);
		$summary = $this->buildStateRepository->getSummary();

		return $this->render('index.html.twig', [
			'summary'    => $summary,
			'build_jobs' => $allJobs,
			'route_name' => 'index',
			'page'       => $page
		]);
	}
}
