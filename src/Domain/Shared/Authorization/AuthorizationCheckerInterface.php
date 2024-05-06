<?php

namespace App\Domain\Shared\Authorization;

interface AuthorizationCheckerInterface
{
    /** @param mixed $resource */
    public function allows(string $action, $resource): bool;
}
