<?php

namespace App\Domain\Shared\Authorization;

interface AuthorizationInterface
{
    /** @param mixed $resource */
    public function supports(string $action, $resource): bool;

    /** @param mixed $resource */
    public function allows(string $action, $resource): bool;
}
