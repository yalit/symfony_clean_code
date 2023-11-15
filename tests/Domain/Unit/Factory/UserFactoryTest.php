<?php

namespace App\Tests\Domain\Unit\Factory;

use App\Domain\Model\User;
use App\Domain\Model\Enum\UserRole;
use App\Domain\Model\Factory\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testCreateAdmin(): void
    {
        $user = UserFactory::createAdmin("test_name", "test_email");
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::ADMIN, $user->getRole());
    }

    public function testCreateEditor(): void
    {
        $user = UserFactory::createEditor("test_name", "test_email");
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::EDITOR, $user->getRole());
    }

    public function testCreateAuthor(): void
    {
        $user = UserFactory::createAuthor("test_name", "test_email");
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("test_name", $user->getName());
        $this->assertEquals("test_email", $user->getEmail());
        $this->assertEquals(UserRole::AUTHOR, $user->getRole());
    }
}
