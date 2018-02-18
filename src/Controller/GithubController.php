<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\VCSRepository;
use App\Service\Git\GitHubWebHookParserInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\BuildJob;

class GithubController extends Controller
{
	/** @var EntityManagerInterface $entityManager */
	private $entityManager;

	/** @var GitHubWebHookParserInterface $githubWebHookParser */
	private $githubWebHookParser;

	public function __construct(
		EntityManagerInterface $entityManager,
		GitHubWebHookParserInterface $gitHubWebHookParser
	) {
		$this->entityManager       = $entityManager;
		$this->githubWebHookParser = $gitHubWebHookParser;
	}

	/**
	 * This is the route the github web hook will trigger
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function githubHook(Request $request): Response
	{
		$payload = $request->request->get('payload');

		if ($payload === null) {
			return new Response('This is not a GitHub request');
		}

		$signature = $request->server->get('HTTP_X_HUB_SIGNATURE');

		if ($signature === null || !$this->githubWebHookParser->validateSignature($signature)) {
			return new Response('Wrong signature', 401);
		}

		if ($this->processRequest($payload)) {
			return new Response('Successfully added it to the list of todo\'s');
		}

		return new Response('Could not process the request');
	}

	private function processRequest(string $payload): bool
	{
		try {
			$repository = $this->githubWebHookParser->getRepository($payload);

			$newJob = new BuildJob($repository);
			$this->entityManager->persist($newJob);
			$this->entityManager->flush();
			return true;
		} catch (Exception $exception) {
			return false;
		}

	}
}
