<?php

namespace App\Domain\Score\Model;

use App\Domain\Score\Model\Enum\ScoreCategoryType;

class ScoreCategory
{
    public function __construct(
        private readonly string $id,
        private string $name,
        private ScoreCategoryType $type,
        private ?string $description,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getType(): ScoreCategoryType
    {
        return $this->type;
    }

    public function setType(ScoreCategoryType $type): void
    {
        $this->type = $type;
    }
}
