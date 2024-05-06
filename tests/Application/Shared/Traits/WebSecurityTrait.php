<?php

namespace App\Tests\Application\Shared\Traits;

use App\Infrastructure\Doctrine\DataFixtures\DoctrineUserFixtures;
use App\Infrastructure\Security\Model\SecurityUser;
use App\Infrastructure\Security\Provider\SecurityUserProvider;

trait WebSecurityTrait
{
    protected function createUser(string $userName = DoctrineUserFixtures::ADMIN_NAME): SecurityUser
    {
        $userProvider = self::getContainer()->get(SecurityUserProvider::class);
        return $userProvider->loadUserByIdentifier(sprintf(DoctrineUserFixtures::USER_EMAIL, $userName));
    }
}
