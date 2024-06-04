<?php

namespace App\Domain\Score\Action;

use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Shared\Action\ActionInput;

class CreateScoreInput implements ActionInput
{
    /**
     * @param ScoreCategory[] $categories
     * @param Composer[] $composers
     * @param ScoreIdentification[] $identifications
     * @param ScoreFile[] $scoreFiles
     */
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly array $categories = [],
        private readonly array $composers = [],
        private readonly array $identifications = [],
        private readonly array $scoreFiles = [],
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /** @return ScoreCategory[] */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /** @return Composer[] */
    public function getComposers(): array
    {
        return $this->composers;
    }

    /** @return ScoreIdentification[] */
    public function getIdentifications(): array
    {
        return $this->identifications;
    }

    /** @return ScoreFile[] */
    public function getScoreFiles(): array
    {
        return $this->scoreFiles;
    }
}
