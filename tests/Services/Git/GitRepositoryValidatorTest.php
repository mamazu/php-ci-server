<?php

/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 14/02/18
 * Time: 23:50
 */

namespace App\Tests\Services\Git;

use App\Services\VCSRepositoryValidator;
use PHPUnit\Framework\TestCase;
use App\Service\Git\GitRepositoryValidator;
use App\Exception\InvalidRepositoryURLException;

class GitRepositoryValidatorTest extends TestCase
{
    /** @var VCSRepositoryValidator */
    private $gitRepositoryValidator;

    public function setUp()
    {
        $this->gitRepositoryValidator = new GitRepositoryValidator();
    }

    /** @dataProvider dataValidateRepositoryURL */
    public function testValidateRepositoryURL(string $repositoryURL)
    {
        self::expectException(InvalidRepositoryURLException::class);

        $this->gitRepositoryValidator->validateRepositoryURL($repositoryURL);
    }

    public function dataValidateRepositoryURL() : array
    {
        return [
            // HTTP
            'wrong website' => ['https://test.com/mamazu/lpg'],
            'https invalid trailing' => ['https://github.com/mamazu/lpg/abc'],
            'ftp invalid' => ['ftp://github.com/mamazu/lpg'],

            // SSH
            'ssh invalid' => ['git@github.com/mamazu/lpg'],
            'wrong user' => ['hello@github.com:mamazu/lpg'],
        ];
    }

    /** @dataProvider dataValidRepositoryURL */
    public function testValidRepositoryURL(string $repositoryURL)
    {
        $this->gitRepositoryValidator->validateRepositoryURL($repositoryURL);

        self::assertTrue(true);
    }

    public function dataValidRepositoryURL() : array
    {
        return [
            'https' => ['https://github.com/mamazu/lpg.git'],
            'ssh' => ['git@github.com:mamazu/lpg.git'],
        ];
    }

    public function testValidateRevisionNumber()
    {
        TestCase::assertTrue(true);
    }
}
