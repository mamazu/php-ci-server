<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 24/02/18
 * Time: 19:18
 */

namespace App\Tests\Services\General;

use App\Service\General\VCSValidatorAccumulator;
use App\Service\General\VCSValidatorAccumulatorInterface;
use App\Service\Git\GitRepositoryValidator;
use App\Service\VCSRepositoryValidatorInterface;
use PHPUnit\Framework\TestCase;

class VCSValidatorAccumulatorTest extends TestCase
{
    /** @var VCSValidatorAccumulatorInterface */
    private $accumulator;

    protected function setUp()
    {
        $this->accumulator = new VCSValidatorAccumulator();
    }

    public function testSet()
    {
        $validator = self::createMock(VCSRepositoryValidatorInterface::class);
        $this->accumulator->set('someType', $validator);

        self::assertEquals($validator, $this->accumulator->get('someType'));
    }

    public function testGetWithNonExisting()
    {
        self::assertNull($this->accumulator->get('something'));
    }
}
