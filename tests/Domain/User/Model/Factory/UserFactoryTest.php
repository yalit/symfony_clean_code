<?php

namespace App\Tests\Domain\User\Model\Factory;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Model\User;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testCreateAdmin(): void
    {
        $user = UserFactory::createAdmin("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::ADMIN, $user->getRole());
    }

    public function testCreateEditor(): void
    {
        $user = UserFactory::createEditor("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::EDITOR, $user->getRole());
    }

    public function testCreateAuthor(): void
    {
        $user = UserFactory::createAuthor("test_name", "test_email", 'Password123)');
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::AUTHOR, $user->getRole());
    }
}
