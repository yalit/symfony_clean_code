<?php

namespace App\Tests\Domain\User\Service;

use App\Domain\User\Model\User;
use App\Domain\User\Service\PasswordHasherInterface;

class TestPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword, User $user): string
    {
        return implode('', array_reverse(str_split($plainPassword)));
    }

    public function isPasswordValid(string $plainPassword, User $user): bool
    {
        return $this->hash($plainPassword, $user) === $user->getPassword();
    }
}
