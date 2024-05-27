<?php

namespace App\Tests\Application\Controller;

use App\Infrastructure\Doctrine\DataFixtures\User\DoctrineUserFixtures;
use App\Tests\Application\Shared\Traits\WebSecurityTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    use WebSecurityTrait;
    public function testIndexPageNotAuthenticated(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects('/login');
    }

    public function testIndexPageAuthenticated(): void
    {
        $client = static::createClient();
        $client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
