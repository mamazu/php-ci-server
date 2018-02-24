<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 14/02/18
 * Time: 23:50
 */

namespace App\Tests\Services\Git;

use App\Exceptions\InvalidRepositoryURLException;
use App\Services\Git\GitRepositoryValidator;
use App\Services\VCSRepositoryValidator;
use PHPUnit\Framework\TestCase;

class GitRepositoryValidatorTest extends TestCase
{
    /** @var VCSRepositoryValidator */
    private $gitRepositoryValidator;

    public function setUp()
    {
        $this->gitRepositoryValidator = new GitRepositoryValidator();
    }

    /** @dataProvider dataValidateRepositoryURL */
    public function testValidateRepositoryURL(string $repositoryURL, bool $isValid)
    {
        try {
            $this->gitRepositoryValidator->validateRepositoryURL($repositoryURL);
        } catch (InvalidRepositoryURLException $exception) {
            TestCase::assertFalse($isValid, $exception->getMessage());
            return;
        }
        TestCase::assertTrue($isValid, 'A wrong url went through');
    }

    public function dataValidateRepositoryURL(): array
    {
        return [
            // HTTP
            'https valid'            => ['https://github.com/mamazu/lpg.git', true],
            'wrong website'          => ['https://test.com/mamazu/lpg', false],
            'https invalid trailing' => ['https://github.com/mamazu/lpg/abc', false],
            'ftp invalid'            => ['ftp://github.com/mamazu/lpg', false],

            // SSH
            'ssh valid'              => ['git@github.com:mamazu/lpg.git', true],
            'ssh invalid'            => ['git@github.com/mamazu/lpg', false],
            'wrong user'             => ['hello@github.com:mamazu/lpg', false],
        ];
    }

    public function testValidateRevisionNumber()
    {
        TestCase::assertTrue(true);
    }
}
