<?php

namespace App\Tests\Domain\Shared\Authorization;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Authorization\AuthorizationInterface;

class TestAuthorizationChecker implements AuthorizationCheckerInterface
{
    /** @var AuthorizationInterface[] $authorizations */
    private array $authorizations = [];

    public function allows(string $action, $resource): bool
    {
        foreach ($this->authorizations as $authorization) {
            if ($authorization->supports($action, $resource)) {
                return $authorization->allows($action, $resource);
            }
        }

        return false;
    }

    public function addAuthorization(AuthorizationInterface $authorization): void
    {
        $this->authorizations[$authorization::class] = $authorization;
    }
}
