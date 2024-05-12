<?php

namespace App\Tests\Infrastructure\Unit\Security\Provider;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Security\Provider\SecurityUserProvider;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use Monolog\Test\TestCase;

class SecurityUserProviderTest extends TestCase
{
    private SecurityUserProvider $securityUserProvider;
    private UserRepositoryInterface $userRepository;
    private TestPasswordHasher $passwordHasher;

    protected function setUp(): void
    {
        $this->userRepository = new InMemoryTestUserRepository();
        $this->passwordHasher = new TestPasswordHasher();

        $this->securityUserProvider = new SecurityUserProvider($this->userRepository);
    }

    public function testLoadUserByIdentifier(): void
    {
        $user = new User(
            'user-id',
            'user-name',
            'user-email@email.com',
            UserRole::ADMIN,
        );
        $user->setPassword($this->passwordHasher->hash('user-password', $user));
        $this->userRepository->save($user);

        $securityUser = $this->securityUserProvider->loadUserByIdentifier('user-email@email.com');
        self::assertNotNull($securityUser);
        self::assertEquals('user-id', $securityUser->getId());
        self::assertEquals('user-name', $securityUser->getName());
        self::assertTrue($this->passwordHasher->isPasswordValid('user-password', $securityUser));
    }

    public function testLoadUserByIdentifierWithInvalidUser(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');

        $this->securityUserProvider->loadUserByIdentifier('test@email.com');
    }

    public function testUpgradePassword(): void
    {
        $user = new User(
            'user-id',
            'user-name',
            'user-email@email.com',
            UserRole::ADMIN,
        );
        $user->setPassword($this->passwordHasher->hash('user-password', $user));
        $this->userRepository->save($user);

        $securityUser = $this->securityUserProvider->loadUserByIdentifier('user-email@email.com');
        $newPassword = 'new-password';
        $this->securityUserProvider->upgradePassword($securityUser, $this->passwordHasher->hash($newPassword, $securityUser));

        self::assertFalse($this->passwordHasher->isPasswordValid('user-password', $securityUser));
        self::assertTrue($this->passwordHasher->isPasswordValid($newPassword, $securityUser));

        $updatedUser = $this->userRepository->getOneById($securityUser->getId());
        self::assertTrue($this->passwordHasher->isPasswordValid($newPassword, $updatedUser));
    }
}
