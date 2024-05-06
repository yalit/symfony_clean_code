<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Action\DeleteUserAction;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserAction;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Authorization\DeleteUserAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\Factory\UserFactory;
use App\Tests\Domain\Shared\Authorization\TestAuthorizationChecker;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use PHPUnit\Framework\TestCase;

class DeleteUserActionTest extends TestCase
{
    private UserRepositoryInterface $userRepository;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function setUp(): void
    {
        $this->userRepository = new InMemoryTestUserRepository();
        (new DomainTestUserFixtures($this->userRepository, new UserFactory(new TestPasswordHasher())))->load();
        $this->userRepository->setCurrentUser($this->userRepository->findOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL));

        $this->authorizationChecker = new TestAuthorizationChecker();
        $this->authorizationChecker->addAuthorization(new DeleteUserAuthorization($this->userRepository));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepository);
    }


    /** @dataProvider getDeleteUserEmails */
    public function testDeleteUserActionOnNonSelfExistingUser(string $userEmail): void
    {
        $user = $this->userRepository->findOneByEmail($userEmail);
        $userId = $user->getId();

        $deleteUserAction = new DeleteUserAction($this->userRepository, $this->authorizationChecker);
        $deleteUserAction($this->getDeleteUserInput($userEmail));

        $this->assertNull($this->userRepository->findOneById($userId));
    }

    public function testDeleteUserActionOnSelfExistingUser(): void
    {
        $user = $this->userRepository->findOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL);
        $userId = $user->getId();

        $deleteUserAction = new DeleteUserAction($this->userRepository, $this->authorizationChecker);
        $this->expectException(InvalidRequester::class);
        $deleteUserAction($this->getDeleteUserInput(DomainTestUserFixtures::ADMIN_EMAIL));

        self::assertNotNull($this->userRepository->findOneById($userId));
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function getDeleteUserEmails(): iterable
    {
        yield "Editor" => [DomainTestUserFixtures::EDITOR_EMAIL];
        yield "Author" => [DomainTestUserFixtures::AUTHOR_EMAIL];
    }

    private function getDeleteUserInput(string $userEmail): DeleteUserInput
    {
        return new DeleteUserInput(
            $this->userRepository->findOneByEmail($userEmail),
        );
    }
}
