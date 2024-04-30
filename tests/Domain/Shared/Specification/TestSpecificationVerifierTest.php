<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationVerifierInterface;
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
        $this->specificationVerifier->addSpecification(new TestEvenSpecification());

        self::assertTrue($this->specificationVerifier->satisfies([TestEvenSpecification::class], 2));
    }

    public function testExistingNotSatisfiedSpecification(): void
    {
        $this->specificationVerifier->addSpecification(new TestEvenSpecification());
        self::assertFalse($this->specificationVerifier->satisfies([TestEvenSpecification::class], 1));
    }

    public function testNonExistingSpecification(): void
    {
        self::assertFalse($this->specificationVerifier->satisfies([TestEvenSpecification::class], 2));
    }
}
