<?php

namespace App\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Score;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Shared\Service\Factory\UniqIDFactory;
use DateTimeImmutable;

class ScoreFactory
{
    /**
     * @param ScoreIdentification[] $identifications
     * @param Composer[] $composers
     * @param ScoreCategory[] $categories
     * @param ScoreFile[] $scoreFiles
     */
    public static function create(
        string $title,
        ?string $description = null,
        array $identifications = [],
        array $composers = [],
        array $categories = [],
        array $scoreFiles = [],
    ): Score {
        return new Score(
            id: UniqIDFactory::create("score_"),
            title: $title,
            description: $description ?? '',
            identifications: $identifications,
            composers: $composers,
            categories: $categories,
            scoreFiles: $scoreFiles,
        );
    }
}
