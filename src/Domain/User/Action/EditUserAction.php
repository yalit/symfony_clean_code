<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionInput;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\PasswordHasherInterface;

class EditUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @throws InvalidRequester
     */
    public function __invoke(EditUserInput $input): ?ActionOutput
    {
        if (!$this->isAllowed($input->getUser())) {
            throw new InvalidRequester();
        }
        //TODO : add validator

        $changed = false;
        $user = $input->getUser();
        foreach ($input->getData() as $key => $value) {
            $setter = $this->defineSetter($key);
            if(method_exists(User::class, $setter)) {
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

    private function isAllowed(User $user): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        if ($requester->getRole() === UserRole::ADMIN || $requester->getId() === $user->getId()) {
            return true;
        }

        return false;
    }

    private function defineSetter(string $variableName): string
    {
        return 'set' . ucfirst($variableName);
    }
}
