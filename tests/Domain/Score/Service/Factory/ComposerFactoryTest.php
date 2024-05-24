<?php

namespace App\Tests\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Composer;
use App\Domain\Score\Service\Factory\ComposerFactory;
use PHPUnit\Framework\TestCase;

class ComposerFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $category = ComposerFactory::create("Composer name");
        self::assertInstanceOf(Composer::class, $category);
        self::assertNotNull($category->getId());
        self::assertStringStartsWith("composer_", $category->getId());
        self::assertEquals("Composer name", $category->getName());
    }
}
