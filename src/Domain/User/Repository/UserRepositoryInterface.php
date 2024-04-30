<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Model\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function getOneById(string $id): ?User;

    public function getOneByEmail(string $email): ?User;

    /**
     * @return array<User>
     */
    public function findAll(): array;

    public function delete(string $id): void;

    /** Provides the user that is "logged into" the application */
    public function getCurrentUser(): ?User;
}
