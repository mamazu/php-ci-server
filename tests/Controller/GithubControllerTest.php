<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GithubControllerTest extends WebTestCase
{
	const PATH_TO_TEST = '/github_webhook';

	public function testGitHubControllerWithEmptyPayLoad(){
		$client = self::createClient();

		$client->request('GET', self::PATH_TO_TEST);

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		$content= $client->getResponse()->getContent();

		self::assertEquals('This is not a GitHub request', $content);
	}

	public function testGithubControllerWithInvalidSignature()
	{
		$client = self::createClient();

		$client->request('POST', self::PATH_TO_TEST, ['payload' => 'something'], [], []);

		echo $client->getResponse()->getContent();
		self::assertEquals(401, $client->getResponse()->getStatusCode());
	}
}
