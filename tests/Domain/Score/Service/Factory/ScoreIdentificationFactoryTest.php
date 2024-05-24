<?php

namespace App\Tests\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;
use PHPUnit\Framework\TestCase;

class ScoreIdentificationFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $category = ScoreCategoryFactory::create('category', ScoreCategoryType::IDENTIFICATION);
        $scoreIdentification = ScoreIdentificationFactory::create('I-203', $category);
        self::assertInstanceOf(ScoreIdentification::class, $scoreIdentification);
        self::assertNotNull($scoreIdentification->getId());
        self::assertStringStartsWith('identification_', $scoreIdentification->getId());
        self::assertEquals('I-203', $scoreIdentification->getNumber());
        self::assertSame($category, $scoreIdentification->getCategory());
    }

    public function testCreateWithInCorrectCategory(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('ScoreCategory must be of type IDENTIFICATION');
        $category = ScoreCategoryFactory::create('category', ScoreCategoryType::SCORE);
        ScoreIdentificationFactory::create('I-203', $category);
    }
}
