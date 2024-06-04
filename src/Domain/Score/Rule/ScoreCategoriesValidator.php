<?php

namespace App\Domain\Score\Rule;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;
use Attribute;

class ScoreCategoriesValidator implements RuleValidatorInterface
{

    public function isValid($object, RuleInterface $rule): bool
    {
        if (!($rule instanceof ScoreCategories)) {
            return true;
        }

        if (!is_object($object) || !method_exists($object, 'getCategories')) {
            return true;
        }

        foreach ($object->getCategories() as $category) {
            if ($category->getType() !== ScoreCategoryType::SCORE) {
                return false;
            }
        }

        return true;
    }
}
