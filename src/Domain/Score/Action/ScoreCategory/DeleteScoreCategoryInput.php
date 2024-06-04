<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Shared\Action\ActionInput;

class DeleteScoreCategoryInput implements ActionInput
{
    public function __construct(
        private readonly ScoreCategory     $category,
    ) {}

    public function getCategory(): ScoreCategory
    {
        return $this->category;
    }
}
