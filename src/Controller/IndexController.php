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
		$page = max(intval($page), 0);

		return $this->render('index.html.twig', [
			'summary'    => $this->buildStateRepository->getSummary(),
			'build_jobs' => $this->buildJobRepository->getPaged($page, 100),
			'route_name' => 'index',
			'page'       => $page
		]);
	}
}
