<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Authorization\CreateScoreCategoryAuthorization;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class CreateScoreCategoryAction implements Action
{
    public function __construct(
        private readonly ScoreCategoryRepositoryInterface $scoreCategoryRepository,
        private readonly ValidatorInterface               $validator,
        private readonly AuthorizationCheckerInterface    $authorizationChecker,
    ) {}

    public function __invoke(CreateScoreCategoryInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(CreateScoreCategoryAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to create a score category');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $category = ScoreCategoryFactory::create($input->getName(), $input->getType(), $input->getDescription());
        $this->scoreCategoryRepository->save($category);

        return null;
    }
}
