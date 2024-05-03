<?php

namespace App\Tests\Application\Controller\Security;

use App\Infrastructure\Doctrine\DataFixtures\UserFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPageDisplaysLoginForm(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $crawler = $client->submitForm(
            'Login',
            [
                '_username' => sprintf(UserFixtures::USER_EMAIL, UserFixtures::ADMIN_NAME),
                '_password' => UserFixtures::PASSWORD,
            ],
        );

        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertRouteSame('index');
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $crawler = $client->submitForm(
            'Login',
            [
                '_username' => sprintf(UserFixtures::USER_EMAIL, UserFixtures::ADMIN_NAME),
                '_password' => 'invalid_password',
            ],
        );

        self::assertResponseRedirects('/login');
    }
}
