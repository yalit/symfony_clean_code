<?php

namespace App\Tests\Infrastructure\Integration\Voter;

use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use App\Infrastructure\Voter\SpecificationManagerInterface;
use App\Tests\Shared\Specification\TestEvenSpecification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SpecificationVerifierTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testSatisfiesWithExistingSpecification(): void
    {
        $specificationVerifier = self::getContainer()->get(SpecificationVerifierInterface::class);
        // Manage only the non test Specifications so need to add it manually
        $specificationManager = self::getContainer()->get(SpecificationManagerInterface::class);
        $specificationManager->addSpecification(new TestEvenSpecification());

        self::assertTrue($specificationVerifier->satisfies([new TestEvenSpecification()], 2));
    }

    public function testNotSatisfiesWithNonExistingSpecification(): void
    {
        $specificationVerifier = self::getContainer()->get(SpecificationVerifierInterface::class);

        self::assertFalse($specificationVerifier->satisfies([new TestEvenSpecification()], 2));
    }
}
