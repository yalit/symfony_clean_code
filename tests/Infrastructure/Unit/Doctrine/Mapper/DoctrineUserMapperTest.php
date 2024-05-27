<?php

namespace App\Tests\Infrastructure\Unit\Doctrine\Mapper;

use App\Domain\Shared\Service\Factory\UniqIDFactory;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Infrastructure\Doctrine\Mapper\User\DoctrineUserMapper;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNull;

class DoctrineUserMapperTest extends TestCase
{
    private const TEST_PASSWORD = 'Password123)';
    private DoctrineUserMapper $doctrineUserMapper;

    protected function setUp(): void
    {
        $this->doctrineUserMapper = new DoctrineUserMapper();
    }

    public function testFromDomainEntityWithoutExistingEntity(): void
    {
        $user = $this->getUser('Admin', 'admin@email.com', UserRole::ADMIN);
        $doctrineUser = $this->doctrineUserMapper->fromDomainEntity($user);

        self::assertNotNull($doctrineUser);
        self::assertEquals($user->getId(), $doctrineUser->getId());
        self::assertEquals($user->getName(), $doctrineUser->getName());
        self::assertEquals($user->getEmail(), $doctrineUser->getEmail());
        self::assertEquals($user->getRole(), $doctrineUser->getRole());
        self::assertEquals($user->getPassword(), $doctrineUser->getPassword());
        assertNull($doctrineUser->getPlainPassword());
    }

    public function testFromDomainEntityWithExistingEntity(): void
    {
        $user = $this->getUser('Admin', 'admin@email.com', UserRole::ADMIN);
        $existingDoctrineUser = $this->doctrineUserMapper->fromDomainEntity($user);
        $user->setName('New Name');
        $user->setPassword('New Password');

        $doctrineUser = $this->doctrineUserMapper->fromDomainEntity($user, $existingDoctrineUser);

        self::assertNotNull($doctrineUser);
        self::assertEquals($user->getId(), $doctrineUser->getId());
        self::assertEquals($user->getName(), $doctrineUser->getName());
        self::assertEquals($user->getEmail(), $doctrineUser->getEmail());
        self::assertEquals($user->getRole(), $doctrineUser->getRole());
        self::assertEquals($user->getPassword(), $doctrineUser->getPassword());
        assertNull($doctrineUser->getPlainPassword());
    }

    public function testToDomainEntity(): void
    {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setId(UniqIDFactory::create(UserRole::AUTHOR->value));
        $doctrineUser->setName('Author');
        $doctrineUser->setEmail('author@email.com');
        $doctrineUser->setRole(UserRole::AUTHOR);
        $doctrineUser->setPassword(self::TEST_PASSWORD);

        $user = $this->doctrineUserMapper->toDomainEntity($doctrineUser);

        self::assertNotNull($user);
        self::assertEquals($doctrineUser->getId(), $user->getId());
        self::assertEquals($doctrineUser->getName(), $user->getName());
        self::assertEquals($doctrineUser->getEmail(), $user->getEmail());
        self::assertEquals($doctrineUser->getRole(), $user->getRole());
        self::assertEquals($doctrineUser->getPassword(), $user->getPassword());
    }

    public function testToExistingDomainDto(): void
    {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setId(UniqIDFactory::create(UserRole::AUTHOR->value));
        $doctrineUser->setName('Author');
        $doctrineUser->setEmail('author@email.com');
        $doctrineUser->setRole(UserRole::AUTHOR);
        $doctrineUser->setPlainPassword(self::TEST_PASSWORD);

        $createUserInput = $this->doctrineUserMapper->toDomainDto($doctrineUser, CreateUserInput::class);

        self::assertNotNull($createUserInput);
        self::assertEquals($doctrineUser->getName(), $createUserInput->getName());
        self::assertEquals($doctrineUser->getEmail(), $createUserInput->getEmail());
        self::assertEquals($doctrineUser->getPlainPassword(), $createUserInput->getPlainPassword());
        self::assertEquals($doctrineUser->getRole(), $createUserInput->getRole());
    }

    public function testToNonExistingDomainDto(): void
    {
        $doctrineUser = new DoctrineUser();
        $doctrineUser->setId(UniqIDFactory::create(UserRole::AUTHOR->value));
        $doctrineUser->setName('Author');
        $doctrineUser->setEmail('author@email.com');
        $doctrineUser->setRole(UserRole::AUTHOR);
        $doctrineUser->setPlainPassword(self::TEST_PASSWORD);

        $this->expectException(\InvalidArgumentException::class);
        $this->doctrineUserMapper->toDomainDto($doctrineUser, self::class);
    }

    private function getUser(string $name, string $email, UserRole $role): User
    {
        return new User(
            UniqIDFactory::create($role->value),
            $name,
            $email,
            $role,
            self::TEST_PASSWORD,
        );
    }
}
