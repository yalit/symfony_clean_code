<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use App\Tests\Shared\Specification\NotExistingSpecification;
use App\Tests\Shared\Specification\TestEvenSpecification;
use PHPUnit\Framework\TestCase;

class TestSpecificationVerifierTest extends TestCase
{
    private SpecificationVerifierInterface $specificationVerifier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->specificationVerifier = new TestSpecificationVerifier();
    }

    public function testExistingSatisfiedSpecification(): void
    {
        self::assertTrue($this->specificationVerifier->satisfies([new TestEvenSpecification()], 2));
    }

    public function testExistingNotSatisfiedSpecification(): void
    {
        self::assertFalse($this->specificationVerifier->satisfies([new TestEvenSpecification()], 1));
    }
}
