<?php

namespace App\Tests\Application\Controller\Admin;

use App\Application\Controller\Admin\DoctrineUserCrudController;
use App\Infrastructure\Doctrine\DataFixtures\DoctrineUserFixtures;
use App\Tests\Application\Shared\Traits\WebSecurityTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DoctrineUserCrudControllerTest extends WebTestCase
{
    use WebSecurityTrait;

    private AdminUrlGenerator $adminUrlGenerator;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->adminUrlGenerator = self::getContainer()->get(AdminUrlGenerator::class);
    }

    public function testDisplayCrudList(): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::INDEX));

        $this->assertResponseIsSuccessful();
    }

    private function getCrudUrl(string $crudAction, string $entityId = null): string
    {
        $this->adminUrlGenerator->unsetAll();

        $generator = $this->adminUrlGenerator->setController(DoctrineUserCrudController::class)->setAction($crudAction);

        if ($entityId !== null) {
            $generator->setEntityId($entityId);
        }

        return $generator->generateUrl();
    }

}
