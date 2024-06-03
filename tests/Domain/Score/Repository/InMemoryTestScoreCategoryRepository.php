<?php

namespace App\Tests\Domain\Score\Repository;

use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class InMemoryTestScoreCategoryRepository implements ScoreCategoryRepositoryInterface
{
    /**
     * @var ScoreCategory[]
     */
    private array $categories = [];

    public function save(object $entity): void
    {
        $this->categories[$entity->getId()] = $entity;
    }

    public function getOneById(string $id): ?ScoreCategory
    {
        return $this->categories[$id] ?? null;
    }

    public function getOneByName(string $name): ?ScoreCategory
    {
        foreach ($this->categories as $category) {
            if ($category->getName() === $name) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->categories;
    }

    public function delete(string $id): void
    {
        unset($this->categories[$id]);
    }
}
