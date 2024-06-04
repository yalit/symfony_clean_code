<?php

namespace App\Domain\Score\Action;

use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Score;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Shared\Action\ActionInput;
use App\Domain\Shared\Rule\NotBlankProperty;

class DeleteScoreInput implements ActionInput
{
    public function __construct(
        private readonly Score $score,
    ) {}

    public function getScore(): Score
    {
        return $this->score;
    }
}
