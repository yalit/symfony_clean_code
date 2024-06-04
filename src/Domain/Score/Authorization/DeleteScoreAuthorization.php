<?php

namespace App\Domain\Score\Authorization;

use App\Domain\Score\Action\DeleteScoreInput;
use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Model\Enum\UserRole;

class DeleteScoreAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_delete_score';

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof DeleteScoreInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        return $requester->getRole() === UserRole::EDITOR;
    }
}
