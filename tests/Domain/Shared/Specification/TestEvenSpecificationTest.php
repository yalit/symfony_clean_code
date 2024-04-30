<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Model\Specification\UserUniqueEmailSpecification;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use PHPUnit\Framework\TestCase;

class TestEvenSpecificationTest extends TestCase
{
    public function testIsSatisfiedByEvenNumber(): void
    {
        $specification = new TestEvenSpecification();
        self::assertTrue($specification->isSatisfiedBy(2));
    }

    public function testIsNotSatisfiedByOddNumber(): void
    {
        $specification = new TestEvenSpecification();
        self::assertFalse($specification->isSatisfiedBy(1));
    }

    public function testIsNotSatisfiedByNotANumber(): void
    {
        $specification = new TestEvenSpecification();
        self::assertFalse($specification->isSatisfiedBy("a string"));
    }
}
