<?php

namespace App\Domain\Score\Action;

use App\Domain\Score\Authorization\CreateScoreAuthorization;
use App\Domain\Score\Authorization\UpdateScoreAuthorization;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ScoreFactory;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class UpdateScoreAction implements Action
{
    public function __construct(
        private readonly ScoreRepositoryInterface      $scoreRepository,
        private readonly ValidatorInterface            $validator,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    public function __invoke(UpdateScoreInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(UpdateScoreAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to update a score');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $score = $input->getScore();

        if ($input->getTitle()) {
            $score->setTitle($input->getTitle());
        }

        if ($input->getDescription()) {
            $score->setDescription($input->getDescription());
        }

        if (count($input->getCategories()) > 0) {
            $score->setCategories($input->getCategories());
        }

        if (count($input->getIdentifications()) > 0) {
            $score->setIdentifications($input->getIdentifications());
        }

        if (count($input->getComposers()) > 0) {
            $score->setComposers($input->getComposers());
        }

        if (count($input->getScoreFiles()) > 0) {
            $score->setScoreFiles($input->getScoreFiles());
        }

        $score->setUpdatedAt(new \DateTimeImmutable());
        $this->scoreRepository->save($score);

        return null;
    }
}
