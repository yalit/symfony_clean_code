<?php

namespace App\Domain\Score\Action;

use App\Domain\Score\Authorization\CreateScoreAuthorization;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ScoreFactory;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class CreateScoreAction implements Action
{
    public function __construct(
        private readonly ScoreRepositoryInterface      $scoreRepository,
        private readonly ValidatorInterface            $validator,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function __invoke(CreateScoreInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(CreateScoreAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to create a score');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $score = ScoreFactory::create($input->getTitle(), $input->getDescription(), $input->getIdentifications(), $input->getComposers(), $input->getCategories(), $input->getScoreFiles());
        $this->scoreRepository->save($score);

        return null;
    }
}
