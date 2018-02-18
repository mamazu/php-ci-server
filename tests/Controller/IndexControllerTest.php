<?php
declare(strict_types=1);

namespace App\Tests\Controller;


use PHPUnit\Framework\TestCaseTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
	public function testIndex(){
		$client = self::createClient();

		$client->request('GET', '/');

		self::assertEquals(200, $client->getResponse()->getStatusCode());
	}
}