<?php
declare(strict_types=1);

namespace App\Tests\Controller;


use App\Entity\BuildJobInterface;
use App\Entity\BuildStateInterface;
use App\Entity\VCSRepositoryInterface;
use App\Repository\BuildJobRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
	/** @var BuildJobInterface[] */
	private $buildJobObjects = [];

	public function testIndexWithEmptyRepository()
	{
		$client = $this->setupClient();

		$crawler = $client->request('GET', '/');

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		self::assertEquals('manual', $crawler->filterXPath('//div[@class="pannel"]/span')->text());
		self::assertEquals(0, $crawler->filterXPath('//table[@id="buildtable"]/tbody/tr')->count());
	}

	private function setupClient(): Client
	{
		$client    = self::createClient();
		$container = $client->getContainer();

		$buildJobRepo = self::createMock(BuildJobRepositoryInterface::class);

		$buildJobObjects = &$this->buildJobObjects;
		$buildJobRepo->method('findAll')->willReturnCallback(function () use ($buildJobObjects) : array {
			return $this->buildJobObjects;
		});

		$container->set('App\\Repository\\BuildJobRepository', $buildJobRepo);

		return $client;
	}

	public function testIndexWithBuildJobs()
	{
		$client                = $this->setupClient();
		$this->buildJobObjects = [
			$this->createBuildJob(BuildJobInterface::STATUS_INPROGRESS),
			$this->createBuildJob(BuildJobInterface::STATUS_PENDING),
		];

		$crawler = $client->request('GET', '/');

		$statusCode = $client->getResponse()->getStatusCode();
		if ($statusCode === 500) {
			echo $client->getResponse()->getContent();
		}

		$cellList = $crawler->filterXPath('//table[@id="buildtable"]/tbody/tr/td');
		self::assertEquals(200, $statusCode);
		self::assertEquals('manual', $crawler->filterXPath('//div[@class="pannel"]/span')->text());
		self::assertEquals(2, $crawler->filterXPath('//table[@id="buildtable"]/tbody/tr')->count());
		self::assertEquals('name', trim($cellList->eq(1)->text()));
		self::assertEquals('revision', trim($cellList->eq(2)->text()));
	}

	private function createBuildJob(string $state): BuildJobInterface
	{
		$buildJob = self::createMock(BuildJobInterface::class);

		$buildState = self::createMock(BuildStateInterface::class);

		$repository = self::createMock(VCSRepositoryInterface::class);
		$repository->method('getName')->willReturn('name');
		$repository->method('getRevisionNumber')->willReturn('revision');

		$buildJob->method('getState')->willReturn($buildState);
		$buildJob->method('getRepository')->willReturn($repository);

		return $buildJob;
	}
}