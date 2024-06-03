<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Rule\NotBlankName;
use App\Domain\Shared\Action\ActionInput;
use Symfony\Component\Validator\Constraints\NotBlank;

#[NotBlankName]
class UpdateScoreCategoryInput implements ActionInput
{
    public function __construct(
        private readonly ScoreCategory     $category,
        private readonly ?string            $name = null,
        private readonly ?ScoreCategoryType $type = null,
        private readonly ?string           $description = null,
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?ScoreCategoryType
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCategory(): ScoreCategory
    {
        return $this->category;
    }
}
