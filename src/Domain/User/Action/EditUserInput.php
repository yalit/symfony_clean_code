<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\ActionInput;
use App\Domain\User\Model\User;

class EditUserInput implements ActionInput
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly User $user,
        private readonly array $data,
    )
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
