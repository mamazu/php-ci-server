<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\GitHubService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\BuildJob;

class GithubController extends Controller
{
	/** @var EntityManagerInterface $entityManager */
	private $entityManager;

	/** @var GitHubService $gitHubService */
	private $githubService;

	public function __construct(
		EntityManagerInterface $entityManager,
		GitHubService $githubService
	) {
		$this->entityManager = $entityManager;
		$this->githubService = $githubService;
	}

	/**
	 * @Route("/github_hook", name="github_hook")
	 */
	public function githubHook(Request $request) : Response
	{
		$payload = $request->request->get('payload');

		if ($payload === null) {
			return new Response('This is not a GitHub request');
		}

		$this->githubService->setPayload($payload);

		$signiture = $request->server->get('HTTP_X_HUB_SIGNATURE');
		if (!$this->githubService->validateSigniture($signiture)) {
			return new Response('Wrong signiture', 401);
		}

		if ($this->processRequest($payload)) {
			return new Response('Sucessfully added it to the list of todos');
		}

		return new Response('Could not process the request');
	}

	private function processRequest(string $payload) : bool
	{
		$commitId = $this->githubService->getCommitId();
		$cloneUrl = $this->githubService->getCloneUrl();

		if (is_null($commitId) || is_null($cloneUrl)) {
			return false;
		}

		$newJob = new BuildJob($commitId, $cloneUrl);
		$this->entityManager->persist($newJob);
		$this->entityManager->flush();

		return true;
	}
}
