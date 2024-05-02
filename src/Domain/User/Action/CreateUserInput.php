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
        private readonly string   $plainPassword,
        private readonly UserRole $role,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }
}
