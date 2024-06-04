<?php

namespace App\Domain\Score\Authorization;

use App\Domain\Score\Action\CreateScoreInput;
use App\Domain\Score\Action\UpdateScoreInput;
use App\Domain\Score\Model\Score;
use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class UpdateScoreAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_create_score';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @inheritDoc
     */
    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof UpdateScoreInput;
    }

    /**
     * @param Score $resource
     */
    protected function allowsNonAdmin(string $action, $resource): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        return $requester->getRole() === UserRole::EDITOR;
    }
}
