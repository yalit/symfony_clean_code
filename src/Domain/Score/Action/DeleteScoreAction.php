<?php

namespace App\Domain\Score\Action;

use App\Domain\Score\Authorization\CreateScoreAuthorization;
use App\Domain\Score\Authorization\DeleteScoreAuthorization;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ScoreFactory;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class DeleteScoreAction implements Action
{
    public function __construct(
        private readonly ScoreRepositoryInterface      $scoreRepository,
        private readonly ValidatorInterface            $validator,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function __invoke(DeleteScoreInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(DeleteScoreAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to delete a score');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $this->scoreRepository->delete($input->getScore()->getId());
        return null;
    }
}
