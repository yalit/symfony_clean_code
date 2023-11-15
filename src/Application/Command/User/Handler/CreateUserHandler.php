<?php

namespace App\Application\Command\User\Handler;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Command\CommandInterface;
use App\Application\Command\CommandOutputInterface;
use App\Application\Command\Shared\ErrorCommandOutput;
use App\Application\Command\Shared\NoActionCommandOutput;
use App\Application\Command\Shared\SuccessCommandOutput;
use App\Application\Command\User\CreateUser;
use App\Domain\Model\Enum\UserRole;
use App\Domain\Model\Factory\UserFactory;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class CreateUserHandler implements CommandHandlerInterface
{
    private PasswordHasherInterface $userPasswordHasher;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherFactoryInterface $userPasswordHasherFactory
    )
    {
        $this->userPasswordHasher = $this->userPasswordHasherFactory->getPasswordHasher(User::class);
    }

    public function __invoke(CommandInterface $command): CommandOutputInterface
    {
        if (!$command instanceof CreateUser) {
            return new NoActionCommandOutput();
        }

        $password = $this->userPasswordHasher->hash($command->getPassword());

        $user = match($command->getRole()) {
            UserRole::ADMIN => UserFactory::createAdmin($command->getName(), $command->getEmail(), $password),
            UserRole::EDITOR => UserFactory::createEditor($command->getName(), $command->getEmail(), $password),
            UserRole::AUTHOR => UserFactory::createAuthor($command->getName(), $command->getEmail(), $password),
            default => throw new \Exception('Unexpected match value'),
        };

        $this->userRepository->save($user);

        return new SuccessCommandOutput();
    }
}
