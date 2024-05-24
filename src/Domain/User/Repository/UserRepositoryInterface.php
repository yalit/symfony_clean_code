<?php

namespace App\Domain\User\Repository;

use App\Domain\Shared\Repository\DomainRepositoryInterface;
use App\Domain\User\Model\User;

/**
 * @template-extends DomainRepositoryInterface<User>
 */
interface UserRepositoryInterface extends DomainRepositoryInterface
{
    public function getOneByEmail(string $email): ?User;

    /** Provides the user that is "logged into" the application */
    public function getCurrentUser(): ?User;
}
