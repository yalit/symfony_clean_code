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
        string $description,
        array $identifications,
        array $composers,
        array $categories,
        array $scoreFiles,
    ): Score {
        $date = new DateTimeImmutable();
        return new Score(
            id: UniqIDFactory::create("score_"),
            title: $title,
            description: $description,
            identifications: $identifications,
            composers: $composers,
            categories: $categories,
            scoreFiles: $scoreFiles,
            createdAt: $date,
            updatedAt: $date,
        );
    }
}
