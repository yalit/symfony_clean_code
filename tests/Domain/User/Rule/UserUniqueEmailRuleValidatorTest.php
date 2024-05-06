<?php

namespace App\Tests\Domain\User\Rule;

use App\Domain\User\Rule\UserUniqueEmailRule;
use App\Domain\User\Rule\UserUniqueEmailRuleValidator;
use App\Domain\User\Service\Factory\UserFactory;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use PHPUnit\Framework\TestCase;

class UserUniqueEmailRuleValidatorTest extends TestCase
{
    private UserFactory $userFactory;

    protected function setUp(): void
    {
        $this->userFactory = new UserFactory(new TestPasswordHasher());
    }

    public function testIsSatisfiedByWithNoExistingUsers(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $validator = new UserUniqueEmailRuleValidator($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($validator->isValid($testUser, new UserUniqueEmailRule()));
    }


    public function testIsSatisfiedByWithExistingUser(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save($this->userFactory->createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $validator = new UserUniqueEmailRuleValidator($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'another@email.com', 'Password123)');
        self::assertTrue($validator->isValid($testUser, new UserUniqueEmailRule()));
    }

    public function testIsNotSatisfiedByExistingEmail(): void
    {
        $userRepository = new InMemoryTestUserRepository();
        $userRepository->save($this->userFactory->createAdmin('Admin', 'admin@email.com', 'Password123)'));

        $validator = new UserUniqueEmailRuleValidator($userRepository);

        $testUser = $this->userFactory->createAdmin('other', 'admin@email.com', 'Password123)');
        self::assertFalse($validator->isValid($testUser, new UserUniqueEmailRule()));
    }
}
