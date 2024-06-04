<?php

namespace App\Domain\Score\Authorization\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\DeleteScoreCategoryInput;
use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteScoreCategoryAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_delete_score_category';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @inheritDoc
     */
    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof DeleteScoreCategoryInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        return false;
    }
}
