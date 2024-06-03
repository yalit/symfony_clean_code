<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Authorization\ScoreCategory\DeleteScoreCategoryAuthorization;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class DeleteScoreCategoryAction implements Action
{
    public function __construct(
        private readonly ScoreCategoryRepositoryInterface $scoreCategoryRepository,
        private readonly ValidatorInterface               $validator,
        private readonly AuthorizationCheckerInterface    $authorizationChecker,
    ) {}

    public function __invoke(DeleteScoreCategoryInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(DeleteScoreCategoryAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to delete a score category');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $this->scoreCategoryRepository->delete($input->getCategory()->getId());

        return null;
    }
}
