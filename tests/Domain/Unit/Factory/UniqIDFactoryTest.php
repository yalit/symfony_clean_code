<?php

namespace App\Tests\Domain\Unit\Factory;

use App\Domain\Model\Factory\UniqIDFactory;
use PHPUnit\Framework\TestCase;

class UniqIDFactoryTest extends TestCase
{
    public function testCreateIsString(): void
    {
        $this->assertIsString(UniqIDFactory::create());
        $this->assertIsString(UniqIDFactory::create("author"));
    }

    public function testCreateIsUnique(): void
    {
        $this->assertNotEquals(UniqIDFactory::create(), UniqIDFactory::create());
        $this->assertNotEquals(UniqIDFactory::create("author"), UniqIDFactory::create("author"));
    }

    public function testCreateWithPrefix(): void
    {
        $this->assertStringStartsWith("test_prefix", UniqIDFactory::create("test_prefix"));
    }
}
