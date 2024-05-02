<?php

namespace App\Tests\Infrastructure\Unit\Voter;

use App\Infrastructure\Voter\SpecificationManager;
use App\Infrastructure\Voter\SpecificationManagerInterface;
use App\Tests\Shared\Specification\TestEvenSpecification;
use PHPUnit\Framework\TestCase;

class SpecificationManagerTest extends TestCase
{
    private SpecificationManagerInterface $specificationManager;

    protected function setUp(): void
    {
        $this->specificationManager = new SpecificationManager();
    }

    public function testAddSpecification(): void
    {
        self::assertCount(0, $this->specificationManager->getSpecifications());
        $this->specificationManager->addSpecification(new TestEvenSpecification());
        self::assertCount(1, $this->specificationManager->getSpecifications());
    }

    public function testGetSpecification(): void
    {
        self::assertNull($this->specificationManager->getSpecification(TestEvenSpecification::class));
        $this->specificationManager->addSpecification(new TestEvenSpecification());
        self::assertNotNull($this->specificationManager->getSpecification(TestEvenSpecification::class));
    }

    public function testHasSpecification(): void
    {
        self::assertFalse($this->specificationManager->hasSpecification(TestEvenSpecification::class));
        $this->specificationManager->addSpecification(new TestEvenSpecification());
        self::assertTrue($this->specificationManager->hasSpecification(TestEvenSpecification::class));
    }

    public function testGetSpecifications(): void
    {
        self::assertCount(0, $this->specificationManager->getSpecifications());
        $this->specificationManager->addSpecification(new TestEvenSpecification());
        self::assertCount(1, $this->specificationManager->getSpecifications());
    }
}
