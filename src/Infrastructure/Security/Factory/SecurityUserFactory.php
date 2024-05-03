<?php

namespace App\Infrastructure\Security\Factory;

use App\Domain\User\Model\User;
use App\Infrastructure\Security\Model\SecurityUser;

class SecurityUserFactory
{
    public static function createFromUser(User $user): SecurityUser
    {
        return new SecurityUser(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getRole(),
            $user->getPassword(),
        );
    }
}
