<?php

namespace App\Tests\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\Score;
use App\Domain\Score\Service\Factory\ComposerFactory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreFactory;
use App\Domain\Score\Service\Factory\ScoreFileFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;
use App\Tests\Domain\Score\Fixtures\DomainTestScoreFileFixtures;
use PHPUnit\Framework\TestCase;

class ScoreFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $composer = ComposerFactory::create('Composer 1');
        $scoreCategory = ScoreCategoryFactory::create('Score Category', ScoreCategoryType::SCORE);
        $idCategory = ScoreCategoryFactory::create('Identification Category', ScoreCategoryType::IDENTIFICATION);
        $identification = ScoreIdentificationFactory::create('T-123', $idCategory);
        $file = ScoreFileFactory::create(DomainTestScoreFileFixtures::TEST_FILE_PATH);

        $score = ScoreFactory::create(
            'Score title',
            'Score description',
            [$identification],
            [$composer],
            [$scoreCategory],
            [$file],
        );

        $this->assertInstanceOf(Score::class, $score);
        self::assertNotNull($score->getId());
        self::assertStringStartsWith('score_', $score->getId());

        self::assertEquals('Score title', $score->getTitle());
        self::assertEquals('Score description', $score->getDescription());

        self::assertCount(1, $score->getIdentifications());
        $scoreIdentification = $score->getIdentifications()[0];
        self::assertSame($identification, $scoreIdentification);

        self::assertCount(1, $score->getComposers());
        $scoreComposer = $score->getComposers()[0];
        self::assertSame($composer, $scoreComposer);

        self::assertCount(1, $score->getCategories());
        $scoreCategory = $score->getCategories()[0];
        self::assertSame($scoreCategory, $scoreCategory);

        self::assertCount(1, $score->getScoreFiles());
        $scoreFile = $score->getScoreFiles()[0];
        self::assertSame($file, $scoreFile);
    }
}
