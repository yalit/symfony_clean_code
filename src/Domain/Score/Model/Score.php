<?php

namespace App\Domain\Score\Model;

use DateTimeImmutable;

class Score
{
    private readonly DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    public function __construct(
        private readonly string   $id,
        private string            $title,
        private string            $description = '',
        /** @var ScoreIdentification[] */
        private array             $identifications = [],
        /** @var Composer[] */
        private array             $composers = [],
        /** @var ScoreCategory[] */
        private array             $categories = [],
        /** @var ScoreFile[] */
        private array             $scoreFiles = [],
    ) {
        $createdAt = new DateTimeImmutable();
        $this->updatedAt = $createdAt;
        $this->createdAt = $createdAt;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /** @return ScoreIdentification[] */
    public function getIdentifications(): array
    {
        return $this->identifications;
    }

    /** @param ScoreIdentification[] $identifications */
    public function setIdentifications(array $identifications): void
    {
        $this->identifications = $identifications;
    }

    public function addIdentification(ScoreIdentification $identification): void
    {
        if (!array_key_exists($identification->getId(), $this->identifications)) {
            $this->identifications[$identification->getId()] = $identification;
        }
    }

    public function removeIdentification(ScoreIdentification $identification): void
    {
        if (array_key_exists($identification->getId(), $this->identifications)) {
            unset($this->identifications[$identification->getId()]);
        }
    }

    /** @return Composer[] */
    public function getComposers(): array
    {
        return $this->composers;
    }

    /** @param Composer[] $composers */
    public function setComposers(array $composers): void
    {
        $this->composers = $composers;
    }

    public function addComposer(Composer $composer): void
    {
        if (!array_key_exists($composer->getId(), $this->composers)) {
            $this->composers[$composer->getId()] = $composer;
        }
    }

    public function removeComposer(Composer $composer): void
    {
        if (array_key_exists($composer->getId(), $this->composers)) {
            unset($this->composers[$composer->getId()]);
        }
    }

    /** @return ScoreCategory[] */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /** @param ScoreCategory[] $categories */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }

    public function addCategory(ScoreCategory $category): void
    {
        if (!array_key_exists($category->getId(), $this->categories)) {
            $this->categories[$category->getId()] = $category;
        }
    }

    public function removeCategory(ScoreCategory $category): void
    {
        if (array_key_exists($category->getId(), $this->categories)) {
            unset($this->categories[$category->getId()]);
        }
    }

    /** @return ScoreFile[] */
    public function getScoreFiles(): array
    {
        return $this->scoreFiles;
    }

    /** @param ScoreFile[] $scoreFiles */
    public function setScoreFiles(array $scoreFiles): void
    {
        $this->scoreFiles = $scoreFiles;
    }

    public function addScoreFile(ScoreFile $scoreFile): void
    {
        if (!array_key_exists($scoreFile->getId(), $this->scoreFiles)) {
            $this->scoreFiles[$scoreFile->getId()] = $scoreFile;
        }
    }

    public function removeScoreFile(ScoreFile $scoreFile): void
    {
        if (array_key_exists($scoreFile->getId(), $this->scoreFiles)) {
            unset($this->scoreFiles[$scoreFile->getId()]);
        }
    }
}
