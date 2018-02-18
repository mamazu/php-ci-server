<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\GithubController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GithubControllerTest extends WebTestCase
{
	public function testGitHubController(){
		$client = self::createClient();

		$client->request('GET', '/github_webhook');

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		$content= $client->getResponse()->getContent();

		self::assertEquals('This is not a GitHub request', $content);
	}
}
