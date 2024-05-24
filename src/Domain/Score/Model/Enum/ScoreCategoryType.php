<?php

namespace App\Domain\Score\Model\Enum;

enum ScoreCategoryType: string
{
    case SCORE = 'score';
    case IDENTIFICATION = 'identification';
}
