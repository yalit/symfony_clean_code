<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Authorization\EditUserAuthorization;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\PasswordHasherInterface;
use InvalidArgumentException;

class EditUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly ValidatorInterface $validator,
        private readonly AuthorizationCheckerInterface $authorization,
    ) {}

    /**
     * @throws InvalidRequester
     */
    public function __invoke(EditUserInput $input): ?ActionOutput
    {
        if (!$this->authorization->allows(EditUserAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester();
        }

        if (!$this->validator->isValid($input)) {
            throw new InvalidArgumentException('Invalid input data for EditUserAction');
        }

        $changed = false;
        $userId = $input->getUserId();
        $user = $this->userRepository->getOneById($userId);
        foreach ($input->getData() as $key => $value) {
            $setter = $this->defineSetter($key);
            $getter = $this->defineGetter($key);
            if(
                method_exists(User::class, $setter)
                && method_exists(User::class, $getter)
                && $user->$getter() !== $value
            ) {
                $user->$setter($value);
                $changed = true;
            }
        }

        if ($input->getNewPassword() !== null) {
            $user->setPassword($this->passwordHasher->hash($input->getNewPassword(), $user));
            $changed = true;
        }

        if ($changed) {
            $this->userRepository->save($user);
        }

        return null;
    }

    private function defineSetter(string $variableName): string
    {
        return 'set' . ucfirst($variableName);
    }

    private function defineGetter(int|string $key): string
    {
        return 'get' . ucfirst($key);
    }
}
