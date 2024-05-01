<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\ActionInput;
use App\Domain\User\Model\User;

class DeleteUserInput implements ActionInput
{
    public function __construct(
        private readonly User $user,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }
}
