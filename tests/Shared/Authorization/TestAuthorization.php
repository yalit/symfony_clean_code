<?php

namespace App\Tests\Shared\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;

class TestAuthorization implements AuthorizationInterface
{
    public const TEST_ACTION = 'test_action';
    public const TEST_RESOURCE = 'test_resource';

    public function supports(string $action, $resource): bool
    {
        return $action === self::TEST_ACTION;
    }

    public function allows(string $action, $resource): bool
    {
        return $resource === self::TEST_RESOURCE;
    }
}
