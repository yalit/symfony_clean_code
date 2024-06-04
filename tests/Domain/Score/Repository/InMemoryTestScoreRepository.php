<?php

namespace App\Tests\Domain\Score\Repository;

use App\Domain\Score\Model\Score;
use App\Domain\Score\Repository\ScoreRepositoryInterface;

class InMemoryTestScoreRepository implements ScoreRepositoryInterface
{
    /**
     * @var Score[]
     */
    private array $scores = [];

    public function save(object $entity): void
    {
        $this->scores[$entity->getId()] = $entity;
    }

    public function getOneById(string $id): ?Score
    {
        return $this->scores[$id] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->scores;
    }

    public function delete(string $id): void
    {
        unset($this->scores[$id]);
    }
}
