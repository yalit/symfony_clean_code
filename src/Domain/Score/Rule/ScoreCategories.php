<?php

namespace App\Domain\Score\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ScoreCategories implements RuleInterface
{

    public function getValidatorClass(): string
    {
        return ScoreCategoriesValidator::class;
    }

    public function getErrorMessage(): string
    {
        return 'Invalid score categories, they should be of type SCORE';
    }
}
