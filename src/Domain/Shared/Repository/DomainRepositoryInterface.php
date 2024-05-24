<?php

namespace App\Domain\Shared\Repository;

/**
 * @template T of object
 */
interface DomainRepositoryInterface
{
    /**
     * @return ?T
     */
    public function getOneById(string $id): ?object;

    /**
     * @return array<T>
     */
    public function getAll(): array;

    /**
     * @param T $entity
     */
    public function save(object $entity): void;

    public function delete(string $id): void;
}
