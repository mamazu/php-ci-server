<?php
declare(strict_types=1);

namespace App\Tests\Controller;


use App\Entity\BuildJobInterface;
use App\Entity\BuildStateInterface;
use App\Entity\VCSRepositoryInterface;
use App\Repository\BuildJobRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildJobControllerTest extends WebTestCase
{
	/** @var BuildJobInterface[] */
	private $buildJobObjects = [];

	public function testIndexWithEmptyRepository()
	{
		$client = $this->setupClient();

		$crawler = $client->request('GET', '/');

		self::assertEquals(200, $client->getResponse()->getStatusCode());
		self::assertEquals('manual', $crawler->filterXPath('//div[@class="panel"]/span')->text());
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
		$buildJobRepo->method('getPaged')->willReturnCallback(function ($page, $pageSize) {
			return array_slice($this->buildJobObjects, 0, $pageSize);
		});

		$container->set('App\\Repository\\BuildJobRepository', $buildJobRepo);

		return $client;
	}

	public function testIndexWithBuildJobs()
	{
		$client                = $this->setupClient();
		$this->buildJobObjects = [
			$this->createBuildJob(BuildStateInterface::STATUS_INPROGRESS),
			$this->createBuildJob(BuildStateInterface::STATUS_PENDING),
		];

		$crawler = $client->request('GET', '/');

		$statusCode = $client->getResponse()->getStatusCode();
		if ($statusCode === 500) {
			echo $client->getResponse()->getContent();
		}

		$cellList = $crawler->filterXPath('//table[@id="buildtable"]/tbody/tr/td');
		self::assertEquals(200, $statusCode);
		self::assertEquals('manual', $crawler->filterXPath('//div[@class="panel"]/span')->text());
		self::assertEquals(2, $crawler->filterXPath('//table[@id="buildtable"]/tbody/tr')->count());
		self::assertEquals('name', trim($cellList->eq(1)->text()));
		self::assertEquals('revision', trim($cellList->eq(2)->text()));
	}

	private function createBuildJob(string $state): BuildJobInterface
	{
		$buildState = self::createConfiguredMock(BuildStateInterface::class, ['getName' => $state]);

		$repository = self::createConfiguredMock(
			VCSRepositoryInterface::class,
			[
				'getName'           => 'name',
				'getRevisionNumber' => 'revision'
			]
		);

		$buildJob = self::createConfiguredMock(
			BuildJobInterface::class,
			[
				'getState'      => $buildState,
				'getRepository' => $repository,
				'isDone'        => $state === BuildStateInterface::STATUS_DONE,
				'isCanceled'    => $state === BuildStateInterface::STATUS_CANCELED,
				'getCreator'    => 'test'
			]
		);

		return $buildJob;
	}
}