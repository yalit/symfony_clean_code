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
        private readonly ?string $newPassword = null,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }
}
