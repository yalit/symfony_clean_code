<?php

namespace App\Domain\User\Service;

use App\Domain\User\Model\User;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword, User $user): string;
    public function isPasswordValid(string $plainPassword, User $user): bool;
}
