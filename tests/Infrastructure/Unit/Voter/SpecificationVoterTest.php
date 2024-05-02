<?php

namespace App\Tests\Infrastructure\Unit\Voter;

use App\Infrastructure\Voter\SpecificationManagerInterface;
use App\Infrastructure\Voter\SpecificationVoter;
use App\Tests\Shared\Specification\NotExistingSpecification;
use App\Tests\Shared\Specification\TestEvenSpecification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SpecificationVoterTest extends TestCase
{
    public function testGrantedVoteOnExistingSpecification(): void
    {
        $specificationManager = $this->getMockSpecificationManager();
        $specificationManager->method('getSpecification')
            ->with(TestEvenSpecification::class)
            ->willReturn(new TestEvenSpecification())
        ;

        $voter = new SpecificationVoter($specificationManager);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $voter->vote(new NullToken(), 2, [TestEvenSpecification::class]));
    }

    public function testDeniedVoteOnExistingSpecification(): void
    {
        $specificationManager = $this->getMockSpecificationManager();
        $specificationManager->method('getSpecification')
            ->with(TestEvenSpecification::class)
            ->willReturn(new TestEvenSpecification())
        ;

        $voter = new SpecificationVoter($specificationManager);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote(new NullToken(), 1, [TestEvenSpecification::class]));
    }

    public function testAbstainVoteOnNonExistingSpecification(): void
    {
        $specificationManager = $this->getMockSpecificationManager();

        $nonExistingSpecification = NotExistingSpecification::class;
        $specificationManager->method('getSpecification')
            ->with($nonExistingSpecification)
            ->willReturn(null)

        ;

        $voter = new SpecificationVoter($specificationManager);
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote(new NullToken(), 1, [$nonExistingSpecification]));

    }

    private function getMockSpecificationManager(): SpecificationManagerInterface&MockObject
    {
        return $this->createMock(SpecificationManagerInterface::class);
    }
}
