<?php

namespace App\Tests\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use PHPUnit\Framework\TestCase;

class ScoreCategoryFactoryTest extends TestCase
{
    /** @dataProvider getCategoryTypes */
    public function testCreate(ScoreCategoryType $type): void
    {
        $category = ScoreCategoryFactory::create("Test Category", $type, "Test Description");
        self::assertInstanceOf(ScoreCategory::class, $category);
        self::assertNotNull($category->getId());
        self::assertStringStartsWith("category_", $category->getId());
        self::assertEquals("Test Category", $category->getName());
        self::assertEquals("Test Description", $category->getDescription());
        self::assertSame($type, $category->getType());
    }

    /**
     * @return array<array<ScoreCategoryType>>
     */
    public function getCategoryTypes(): array
    {
        return [
            [ScoreCategoryType::SCORE],
            [ScoreCategoryType::IDENTIFICATION],
        ];
    }

    public function testCreateWithNoDescription(): void
    {
        $category = ScoreCategoryFactory::create("Test Category", ScoreCategoryType::SCORE);
        self::assertInstanceOf(ScoreCategory::class, $category);
        self::assertNotNull($category->getId());
        self::assertStringStartsWith("category_", $category->getId());
        self::assertEquals("Test Category", $category->getName());
        self::assertNull($category->getDescription());
    }
}
