<?php

namespace App\Tests\Domain\User\Model\Factory;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Service\Factory\UserFactory;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    private UserFactory $userFactory;
    private PasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $this->passwordHasher = new TestPasswordHasher();
        $this->userFactory = new UserFactory($this->passwordHasher);
    }

    public function testCreateAdmin(): void
    {
        $user = $this->userFactory->createAdmin("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::ADMIN, $user->getRole());
        self::assertNotEquals('Password123)', $user->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid('Password123)', $user));
    }

    public function testCreateEditor(): void
    {
        $user = $this->userFactory->createEditor("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::EDITOR, $user->getRole());
        self::assertNotEquals('Password123)', $user->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid('Password123)', $user));
    }

    public function testCreateAuthor(): void
    {
        $user = $this->userFactory->createAuthor("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::AUTHOR, $user->getRole());
        self::assertNotEquals('Password123)', $user->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid('Password123)', $user));
    }
}
