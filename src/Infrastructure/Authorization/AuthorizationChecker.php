<?php

namespace App\Infrastructure\Authorization;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AuthorizationChecker implements AuthorizationCheckerInterface
{
    public function __construct(private readonly Security $security) {}

    public function allows(string $action, $resource): bool
    {
        return $this->security->isGranted($action, $resource);
    }
}
