<?php

namespace App\Tests\Domain\Score\Action;

use App\Domain\Score\Action\CreateScoreAction;
use App\Domain\Score\Action\CreateScoreInput;
use App\Domain\Score\Action\DeleteScoreAction;
use App\Domain\Score\Action\DeleteScoreInput;
use App\Domain\Score\Action\UpdateScoreAction;
use App\Domain\Score\Action\UpdateScoreInput;
use App\Domain\Score\Authorization\DeleteScoreAuthorization;
use App\Domain\Score\Authorization\UpdateScoreAuthorization;
use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ComposerFactory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Rule\NotBlankPropertyValidator;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Fixtures\DomainTestScoreFixtures;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class DeleteScoreActionTest extends DomainActionTestCase
{
    private ScoreRepositoryInterface $scoreRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreRepository = new InMemoryTestScoreRepository();
        $this->authorizationChecker->addAuthorization(new DeleteScoreAuthorization($this->userRepository));

        (new DomainTestScoreFixtures($this->scoreRepository))->load();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scoreRepository);
    }

    public function testUpdateScore(): void
    {
        $score = array_values($this->scoreRepository->getAll())[0];
        self::assertNotNull($score);

        $input = new DeleteScoreInput($score);
        $action = new DeleteScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser();
        $action($input);

        $updatedScore = $this->scoreRepository->getOneById($score->getId());
        self::assertNull($updatedScore);
    }

    /**
     * @dataProvider provideInvalidUser
     */
    public function testDeleteScoreCategoryWithInvalidUser(?string $email): void
    {
        $input = new CreateScoreInput("title", "description", [], [], [], []);
        $action = new CreateScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser($email);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }

    /**
     * @return array<int, array<string>>
     */
    public function provideInvalidUser(): iterable
    {
        return [[DomainTestUserFixtures::AUTHOR_EMAIL], [null]];
    }
}
