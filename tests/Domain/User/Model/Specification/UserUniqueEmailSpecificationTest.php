<?php

namespace App\Tests\Domain\User\Model\Specification;

use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Specification\UserUniqueEmailSpecification;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use PHPUnit\Framework\TestCase;

class UserUniqueEmailSpecificationTest extends TestCase
{
    public function testIsSatisfiedByWithNoExistingUsers(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = UserFactory::createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($specification->isSatisfiedBy($testUser));
    }


    public function testIsSatisfiedByWithExistingUser(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save(UserFactory::createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = UserFactory::createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($specification->isSatisfiedBy($testUser));
    }

    public function testIsNotSatisfiedByExistingEmail(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save(UserFactory::createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = UserFactory::createAdmin('other', 'admin@email.com', 'Password123)');
        self::assertFalse($specification->isSatisfiedBy($testUser));
    }
}
