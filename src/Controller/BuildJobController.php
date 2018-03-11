<?php

namespace App\Controller;

use App\Entity\BuildJob;
use App\Entity\BuildState;
use App\Entity\BuildStateInterface;
use App\Repository\BuildJobRepositoryInterface;
use App\Repository\BuildStateRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BuildJobController extends Controller
{

	/** @var BuildJobRepositoryInterface */
	private $buildJobRepository;

	/** @var BuildStateRepositoryInterface */
	private $buildStateRepository;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		EntityManagerInterface $entityManager,
		BuildJobRepositoryInterface $buildJobRepository,
		BuildStateRepositoryInterface $buildStateRepository
	) {
		$this->buildJobRepository   = $buildJobRepository;
		$this->buildStateRepository = $buildStateRepository;
		$this->entityManager        = $entityManager;
	}

	public function list(string $page = '0'): Response
	{
		$page = max(intval($page), 0);

		return $this->render('buildjob/list.twig', [
			'summary'    => $this->buildStateRepository->getSummary(),
			'build_jobs' => $this->buildJobRepository->getPaged($page, 100),
			'route_name' => 'list_buildjobs',
			'page'       => $page
		]);
	}

	public function cancelJob(BuildJob $buildJob)
	{
		$buildState = new BuildState($buildJob, BuildStateInterface::STATUS_CANCELED);
		$buildJob->setState($buildState);
		$this->entityManager->flush();

		return $this->redirect($this->generateUrl('list_buildjobs'));
	}

	public function show(BuildJob $buildJob){
		return $this->render('buildjob/show.twig', ['buildjob' => $buildJob]);
	}
}
