<?php

namespace App\Tests\Application\Controller\Admin;

use App\Application\Controller\Admin\DoctrineUserCrudController;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Infrastructure\Doctrine\DataFixtures\User\DoctrineUserFixtures;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use App\Tests\Application\Shared\Traits\WebSecurityTrait;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DoctrineUserCrudControllerTest extends WebTestCase
{
    use WebSecurityTrait;

    private AdminUrlGenerator $adminUrlGenerator;
    private KernelBrowser $client;
    private UserRepositoryInterface $userRepository;
    private PasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->adminUrlGenerator = self::getContainer()->get(AdminUrlGenerator::class);
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $this->passwordHasher = self::getContainer()->get(PasswordHasherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client, $this->adminUrlGenerator, $this->userRepository, $this->passwordHasher);
    }

    public function testDisplayCrudList(): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::INDEX));

        $this->assertResponseIsSuccessful();
    }

    public function testDisplayCrudCreate(): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::NEW));

        $this->assertResponseIsSuccessful();
    }

    /** @dataProvider createUserDataProvider */
    public function testCrudCreateNewUser(string $name, string $email, UserRole $role, string $plainPassword): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::NEW));

        $this->client->submitForm('Create', [
            'DoctrineUser[name]' => $name,
            'DoctrineUser[email]' => $email,
            'DoctrineUser[role]' => $role->value,
            'DoctrineUser[plainPassword]' => $plainPassword,
        ]);

        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $user = $this->userRepository->getOneByEmail($email);
        self::assertNotNull($user);
        self::assertEquals($name, $user->getName());
        self::assertEquals($role, $user->getRole());
        self::assertNotEquals($plainPassword, $user->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid($plainPassword, $user));

    }

    /**
     * @return array<string, array{string, string, UserRole, string}>
     */
    public function createUserDataProvider(): iterable
    {
        yield 'Admin' => ['Admin name', 'admin_test@email.com', UserRole::ADMIN, 'AdminPassword'];
        yield 'Editor' => ['Editor name', 'editor_test@email.com', UserRole::EDITOR, 'EditorPassword'];
        yield 'Author' => ['Author name', 'author_test@email.com', UserRole::AUTHOR, 'AuthorPassword'];

    }

    public function testCrudEditExistingUser(): void
    {
        $email = sprintf(DoctrineUserFixtures::USER_EMAIL, DoctrineUserFixtures::AUTHOR_NAME);
        $user = $this->userRepository->getOneByEmail($email);
        $currentPassword = $user->getPassword();

        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::EDIT, $user->getId()));

        self::assertResponseIsSuccessful();

        $newEmail = 'new_email@email.com';
        $newPassword = 'New_password123';
        $this->client->submitForm('Save changes', [
            'DoctrineUser[email]' => $newEmail,
            'DoctrineUser[plainPassword]' => $newPassword,
        ]);

        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $updatedUser = $this->userRepository->getOneById($user->getId());
        self::assertNotNull($updatedUser);
        self::assertEquals($newEmail, $updatedUser->getEmail());
        self::assertNotEquals($currentPassword, $updatedUser->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid($newPassword, $updatedUser));

        $updatedDoctrineUser = self::getContainer()->get(EntityManagerInterface::class)->getRepository(DoctrineUser::class)->find($updatedUser->getId());
        self::assertNotNull($updatedDoctrineUser);
        self::assertEquals($updatedUser->getEmail(), $updatedDoctrineUser->getEmail());
        self::assertEquals($updatedUser->getPassword(), $updatedDoctrineUser->getPassword());
        self::assertNull($updatedDoctrineUser->getPlainPassword());
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
