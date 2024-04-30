<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\ActionInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;

class CreateUserInput implements ActionInput
{
    public function __construct(
        private readonly string   $name,
        private readonly string   $email,
        private readonly string   $password,
        private readonly UserRole $role,
        private readonly ?User $requester = null
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getRequester(): ?User
    {
        return $this->requester;
    }
}
