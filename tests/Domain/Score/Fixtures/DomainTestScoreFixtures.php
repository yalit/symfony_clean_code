<?php

namespace App\Tests\Domain\Score\Fixtures;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ComposerFactory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreFactory;
use App\Domain\Score\Service\Factory\ScoreFileFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;

class DomainTestScoreFixtures
{
    public function __construct(
        private readonly ScoreRepositoryInterface $scoreRepository,
    ) {}

    public function load(): void
    {
        $category1 = ScoreCategoryFactory::create('Category 1', ScoreCategoryType::SCORE);
        $category2 = ScoreCategoryFactory::create('Category 2', ScoreCategoryType::SCORE);

        $ideCategory = ScoreCategoryFactory::create('Category 3', ScoreCategoryType::IDENTIFICATION);
        $score = ScoreFactory::create(
            'Score Title 1',
            'A nice description',
            [ScoreIdentificationFactory::create('ISRC', $ideCategory)],
            [
                ComposerFactory::create('Composer 1'),
                ComposerFactory::create('Composer 2'),
            ],
            [$category1,$category2],
            [
                ScoreFileFactory::create(DomainTestScoreFileFixtures::TEST_FILE_PATH),
            ]
        );
        $this->scoreRepository->save($score);

    }
}
