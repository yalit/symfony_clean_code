<?php

namespace App\Domain\Score\Model;

use App\Domain\Score\Model\Enum\ScoreCategoryType;

class ScoreIdentification
{
    private ?ScoreCategory $category = null;

    public function __construct(
        private readonly string $id,
        private string          $number,
        ?ScoreCategory          $category = null,
    ) {
        if ($category && $category->getType() !== ScoreCategoryType::IDENTIFICATION) {
            throw new \InvalidArgumentException("ScoreCategory must be of type IDENTIFICATION");
        }
        $this->category = $category;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getCategory(): ?ScoreCategory
    {
        return $this->category;
    }

    public function setCategory(?ScoreCategory $category): void
    {
        $this->category = $category;
    }
}
