<?php

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

    public function testNotValid()
    {
        try {
            $this->webHookParser->setPayload('payload');
        } catch (InvalidPayloadException $exception) {
            TestCase::assertTrue(true);
            return;
        }
        TestCase::assertFalse(true);
    }


    public function testNotValidCommitId()
    {
        $this->webHookParser->setPayload('{"head_commit": {}}');

        $commitId = $this->webHookParser->getCommitId();
        TestCase::assertNull($commitId);
    }

    public function testGetCommitId()
    {
        $this->webHookParser->setPayload('{"head_commit": {"id": "abc"}}');

        $commitId = $this->webHookParser->getCommitId();
        TestCase::assertNotNull($commitId);
        TestCase::assertEquals('abc', $commitId);
    }

    public function testGetCloneUrl()
    {
        $this->webHookParser->setPayload('{"repository": {"clone_url": "https://github.com/something/else"}}');

        $cloneURL = $this->webHookParser->getCloneUrl();
        TestCase::assertNotNull($cloneURL);
        TestCase::assertEquals('https://github.com/something/else', $cloneURL);
    }
}
