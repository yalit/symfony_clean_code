<?php

namespace App\Domain\Score\Model;

use DateTimeImmutable;

class Score
{
    public function __construct(
        private readonly string   $id,
        private string            $title,
        private string            $description,
        /** @var Identification[] */
        private array             $identifications,
        /** @var Composer[] */
        private array             $composers,
        /** @var Category[] */
        private array             $categories,
        /** @var ScoreFile[] */
        private array             $scoreFiles,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

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

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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

    /** @return Identification[] */
    public function getIdentifications(): array
    {
        return $this->identifications;
    }

    public function addIdentification(Identification $identification): void
    {
        if (!array_key_exists($identification->getId(), $this->identifications)) {
            $this->identifications[$identification->getId()] = $identification;
        }
    }

    public function removeIdentification(Identification $identification): void
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

    /** @return Category[] */
    public function getCategories(): array
    {
        return $this->categories;
    }
    public function addCategory(Category $category): void
    {
        if (!array_key_exists($category->getId(), $this->categories)) {
            $this->categories[$category->getId()] = $category;
        }
    }

    public function removeCategory(Category $category): void
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
