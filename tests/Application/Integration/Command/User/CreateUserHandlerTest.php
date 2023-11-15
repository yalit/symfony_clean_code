<?php

namespace App\Tests\Application\Integration\Command\User;

use App\Application\Command\Shared\SuccessCommandOutput;
use App\Application\Command\User\CreateUser;
use App\Application\Command\User\Handler\CreateUserHandler;
use App\Application\Command\User\Output\CreateUserOutput;
use App\Domain\Model\Enum\UserRole;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class CreateUserHandlerTest extends KernelTestCase
{
    public function testCreateUser(): void
    {
        $userName = 'testName';
        $userEmail = 'testEmail@email.com';
        $userPassword = 'testPassword';
        $role = UserRole::AUTHOR;
        $createUser = new CreateUser($userName, $userEmail, $userPassword, $role->value);

        $handler = self::getContainer()->get(CreateUserHandler::class);

        $result = $handler($createUser);
        self::assertInstanceOf(CreateUserOutput::class, $result);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $user = $userRepository->getOneById($result->getUser()->getId());

        self::assertNotNull($user);
        self::assertEquals($userName, $user->getName());
        self::assertEquals($userEmail, $user->getEmail());
        self::assertEquals($role, $user->getRole());

        $passwordHasher = self::getContainer()->get(PasswordHasherFactoryInterface::class)->getPasswordHasher($user);
        self::assertTrue($passwordHasher->verify($user->getPassword(), $userPassword));
    }
}
