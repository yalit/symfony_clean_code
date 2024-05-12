<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\ActionInput;
use App\Domain\User\Model\User;
use App\Domain\User\Rule\UserUniqueDataEmailRule;

#[UserUniqueDataEmailRule]
class EditUserInput implements ActionInput
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly string  $userId,
        private readonly array   $data,
        private readonly ?string $newPassword = null,
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
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
