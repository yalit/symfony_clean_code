<?php

namespace App\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Shared\Service\Factory\UniqIDFactory;

class ScoreIdentificationFactory
{
    public static function create(string $number, ?ScoreCategory $category = null): ScoreIdentification
    {
        if ($category && $category->getType() !== ScoreCategoryType::IDENTIFICATION) {
            throw new \InvalidArgumentException("ScoreCategory must be of type IDENTIFICATION");
        }

        return new ScoreIdentification(
            id: UniqIDFactory::create("identification_"),
            number: $number,
            category: $category,
        );
    }
}
