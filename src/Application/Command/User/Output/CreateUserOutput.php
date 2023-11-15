<?php

namespace App\Application\Command\User\Output;

use App\Application\Command\CommandOutputInterface;
use App\Application\Command\Enum\CommandOutputStatus;
use App\Domain\Model\User;

class CreateUserOutput implements CommandOutputInterface
{
    public function __construct(
        private readonly CommandOutputStatus $status,
        private readonly User $user
    ) {}

    public function getStatus(): CommandOutputStatus
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
