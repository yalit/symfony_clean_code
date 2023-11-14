<?php

namespace App\Tests\Domain\Unit\Factory;

use App\Domain\Model\Author;
use App\Domain\Model\Enum\AuthorRole;
use App\Domain\Model\Factory\AuthorFactory;
use PHPUnit\Framework\TestCase;

class AuthorFactoryTest extends TestCase
{
    public function testCreateAdmin(): void
    {
        $author = AuthorFactory::createAdmin("test_name", "test_email");
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals("test_name", $author->getName());
        $this->assertEquals("test_email", $author->getEmail());
        $this->assertEquals(AuthorRole::ADMIN, $author->getRole());
    }

    public function testCreateEditor(): void
    {
        $author = AuthorFactory::createEditor("test_name", "test_email");
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals("test_name", $author->getName());
        $this->assertEquals("test_email", $author->getEmail());
        $this->assertEquals(AuthorRole::EDITOR, $author->getRole());
    }

    public function testCreateAuthor(): void
    {
        $author = AuthorFactory::createAuthor("test_name", "test_email");
        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals("test_name", $author->getName());
        $this->assertEquals("test_email", $author->getEmail());
        $this->assertEquals(AuthorRole::AUTHOR, $author->getRole());
    }
}
