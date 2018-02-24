<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\VCSRepositoryInterface;
use App\Service\Git\GitHubWebHookParserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GithubControllerTest extends WebTestCase
{
	const PATH_TO_TEST = '/github_webhook';

	private $gitHubWebhookParser;

	public function testWithEmptyPayLoad()
	{
		$client = self::createClient();

		$client->request('GET', self::PATH_TO_TEST);

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		$content = $client->getResponse()->getContent();

		self::assertEquals('This is not a GitHub request', $content);
	}

	public function testWithInvalidSignature()
	{
		$client = self::createClient();

		$client->request('POST', self::PATH_TO_TEST, ['payload' => 'something'], [], []);

		self::assertEquals(401, $client->getResponse()->getStatusCode());
	}

	public function testWithValidSignatureButInvalidContent()
	{
		$client = $this->setupClient();

		$client->request('POST', self::PATH_TO_TEST, ['payload' => 'something'],
						 [], ['HTTP_X_HUB_SIGNATURE' => 'abc']);

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		self::assertEquals('Could not process the request', $client->getResponse()->getContent());
	}

	/**
	 * Setting up a client with a mocked EntityManager and a mocked web hook parser
	 *
	 * @return Client
	 */
	private function setupClient(): Client
	{
		$client    = self::createClient();
		$container = $client->getContainer();

		$this->gitHubWebhookParser = self::createMock(GitHubWebHookParserInterface::class);

		$this->gitHubWebhookParser->method('validateSignature')->willReturnCallback(function (string $signature) {
			return $signature === 'abc';
		});

		$this->gitHubWebhookParser->method('getRepository')->willReturnCallback(function (string $payload) {

			$jsonObject = json_decode($payload, true);

			if ($jsonObject === null) {
				throw new Exception('Not valid json');
			} else {
				return self::createMock(VCSRepositoryInterface::class);
			}
		});


		$entityManager = self::createMock(EntityManagerInterface::class);
		$container->set('App\\Service\\Git\\GitHubWebHookParser', $this->gitHubWebhookParser);
		$container->set('doctrine.orm.default_entity_manager', $entityManager);

		return $client;
	}

	public function testWithValidRequest()
	{
		$client = $this->setupClient();

		$payload = <<<JSON
{
	"repository": "i am here"
}
JSON;
		$client->request('POST', self::PATH_TO_TEST, ['payload' => $payload], [], ['HTTP_X_HUB_SIGNATURE' => 'abc']);

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		self::assertEquals('Successfully added it to the list of todo\'s', $client->getResponse()->getContent());
	}
}
