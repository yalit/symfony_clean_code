<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Shared\Action\ActionInput;

class CreateScoreCategoryInput implements ActionInput
{
    public function __construct(
        private readonly string            $name,
        private readonly ScoreCategoryType $type,
        private readonly ?string           $description,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ScoreCategoryType
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
