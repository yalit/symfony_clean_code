<?php

namespace App\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Shared\Service\Factory\UniqIDFactory;

class ScoreCategoryFactory
{
    public static function create(string $name, ScoreCategoryType $type, ?string $description = null): ScoreCategory
    {
        return new ScoreCategory(
            id: UniqIDFactory::create("category_"),
            name: $name,
            type: $type,
            description: $description,
        );
    }
}
