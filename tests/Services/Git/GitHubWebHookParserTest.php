<?php

declare (strict_types = 1);

namespace App\Tests\Services\Git;

use App\Exception\InvalidPayloadException;
use App\Service\Git\GitHubWebHookParser;
use App\Service\Git\GitHubWebHookParserInterface;
use PHPUnit\Framework\TestCase;

class GitHubWebHookParserTest extends TestCase
{
    /** @var GitHubWebHookParserInterface */
    private $webHookParser;

    public function setup()
    {
        $key = '';
        $this->webHookParser = new GitHubWebHookParser($key);
    }

    public function testImplements()
    {
        TestCase::assertTrue($this->webHookParser instanceof GitHubWebHookParserInterface);
    }

    public function testNotInvalidJSON()
    {
        self::expectException(\Exception::class);
        self::expectExceptionMessage('The payload is not valid json');

        $this->webHookParser->getRepository('{"head_commit"sas: {}}');
    }

    public function testNotValidCommitId()
    {
        self::expectException(\Exception::class);
        self::expectExceptionMessage('Invalid json. Missing information');

        $this->webHookParser->getRepository('{"head_commit": {}}');
    }

    public function testGetCloneUrl()
    {
        $repository = $this->webHookParser->getRepository(<<<JSON
{
	"repository": {
		"name": "something/else",
		"clone_url": "https://github.com/something/else.git"
	},
	"head_commit": {
		"id": "d26b4e91cfb1475c16e65a3f770d672d926a7ac4"
	}
}
JSON);
        self::assertEquals('https://github.com/something/else.git', $repository->getCloneURL());
        self::assertEquals('something/else', $repository->getName());
        self::assertEquals('d26b4e91cfb1475c16e65a3f770d672d926a7ac4', $repository->getRevisionNumber());

    }
}
