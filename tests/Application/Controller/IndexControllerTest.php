<?php

namespace App\Tests\Application\Controller;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Doctrine\DataFixtures\UserFixtures;
use App\Infrastructure\Security\Model\SecurityUser;
use App\Infrastructure\Security\Provider\SecurityUserProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexPageNotAuthenticated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects('/login');
    }

    public function testIndexPageAuthenticated(): void
    {
        $client = static::createClient();
        $client->loginUser($this->createUser());
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    private function createUser(): SecurityUser
    {
        $userProvider = self::getContainer()->get(SecurityUserProvider::class);
        return $userProvider->loadUserByIdentifier(sprintf(UserFixtures::USER_EMAIL, UserFixtures::ADMIN_NAME));
    }
}
