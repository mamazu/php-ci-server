<?php
declare(strict_types=1);

namespace App\Tests\Services\Git;

use App\Exceptions\InvalidPayloadException;
use App\Services\Git\GitHubWebHookParser;
use App\Services\Git\GitHubWebHookParserInterface;
use App\Services\PHPStreamsInterface;
use PHPUnit\Framework\TestCase;

class GitHubWebHookParserTest extends TestCase
{
    /** @var GitHubWebHookParserInterface */
    private $webHookParser;

    /** @var PHPStreamsInterface */
    private $phpStreams;

    public function setup()
    {
        $this->phpStreams    = $this->getMockBuilder(PHPStreamsInterface::class)->getMock();
        $this->webHookParser = new GitHubWebHookParser($this->phpStreams);
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

    /** @dataProvider dataValidateSignature */
    public function testValidateSignature(string $data, string $signature, bool $valid)
    {
        $this->phpStreams->method('getInput')->willReturn($data);

        TestCase::assertEquals($this->webHookParser->validateSignature('', $signature), $valid, 'was not valid');
    }

    public function dataValidateSignature(): array
    {
        return [
            'no data, empty signature' => ['', '', false],
            'no data, some signature'  => ['', 'sha1=fbdb1d1b18aa6c08324b7d64b71fb76370690e1d', true],
            'data, empty signature'    => ['Hello this is test data', '', false],
            'data, signature'          => ['Testing the signature validator', 'sha1=50db89ddeb7537bd056da574514656f57a186efe', true],
        ];
    }

    /** @dataProvider dataValidateSignatureWithKey */
    public function testValidateSignatureWithKey(string $data, string $signature, bool $valid)
    {
        $this->phpStreams->method('getInput')->willReturn($data);



        TestCase::assertEquals($this->webHookParser->validateSignature('abcs', $signature), $valid, 'was not valid');
    }

    public function dataValidateSignatureWithKey(): array
    {
        return [
            'no data, empty signature' => ['', '', false],
            'no data, some signature'  => ['', 'sha1=d7d9c8c80eb8f190512a544479a62b76b2b2121b', true],
            'data, empty signature'    => ['Hello this is test data', '', false],
            'data, signature'          => ['Testing the signature validator', 'sha1=6ef3dad1984b25f0d20ae6d8ea69cc201136f343', true],
        ];
    }
}
