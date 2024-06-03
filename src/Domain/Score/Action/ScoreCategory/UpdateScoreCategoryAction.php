<?php

namespace App\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Authorization\ScoreCategory\UpdateScoreCategoryAuthorization;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\ValidatorInterface;

class UpdateScoreCategoryAction implements Action
{
    public function __construct(
        private readonly ScoreCategoryRepositoryInterface $scoreCategoryRepository,
        private readonly ValidatorInterface               $validator,
        private readonly AuthorizationCheckerInterface    $authorizationChecker,
    ) {}

    public function __invoke(UpdateScoreCategoryInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(UpdateScoreCategoryAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester('You are not allowed to update a score category');
        }

        if (!$this->validator->isValid($input)) {
            throw new ValidationException($this->validator->getErrors());
        }

        $category = $input->getCategory();
        if ($input->getName() !== null) {
            $category->setName($input->getName());
        }
        if ($input->getType()) {
            $category->setType($input->getType());
        }
        if ($input->getDescription()) {
            $category->setDescription($input->getDescription());
        }

        $this->scoreCategoryRepository->save($category);

        return null;
    }
}
