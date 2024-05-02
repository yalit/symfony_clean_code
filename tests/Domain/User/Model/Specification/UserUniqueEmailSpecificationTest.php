<?php

namespace App\Tests\Domain\User\Model\Specification;

use App\Domain\User\Service\Factory\UserFactory;
use App\Domain\User\Specification\UserUniqueEmailSpecification;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use PHPUnit\Framework\TestCase;

class UserUniqueEmailSpecificationTest extends TestCase
{
    private UserFactory $userFactory;

    protected function setUp(): void
    {
        $this->userFactory = new UserFactory(new TestPasswordHasher());
    }

    public function testIsSatisfiedByWithNoExistingUsers(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($specification->isSatisfiedBy($testUser));
    }


    public function testIsSatisfiedByWithExistingUser(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save($this->userFactory->createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($specification->isSatisfiedBy($testUser));
    }

    public function testIsNotSatisfiedByExistingEmail(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save($this->userFactory->createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $specification = new UserUniqueEmailSpecification($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'admin@email.com', 'Password123)');
        self::assertFalse($specification->isSatisfiedBy($testUser));
    }
}
