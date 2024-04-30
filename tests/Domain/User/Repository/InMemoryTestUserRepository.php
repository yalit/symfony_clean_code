<?php

namespace App\Tests\Domain\User\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class InMemoryTestUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    public array $users = [];

    public function save(User $user): void
    {
        $this->users[$user->getId()] = $user;
    }

    public function getOneById(string $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function getOneByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->users;
    }

    public function delete(string $id): void
    {
        unset($this->users[$id]);
    }
}
