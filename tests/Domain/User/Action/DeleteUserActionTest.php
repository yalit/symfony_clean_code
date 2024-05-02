<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Action\DeleteUserAction;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserAction;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use PHPUnit\Framework\TestCase;

class DeleteUserActionTest extends TestCase
{
    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        $this->userRepository = new InMemoryTestUserRepository();
        (new DomainTestUserFixtures($this->userRepository))->load();
        $this->userRepository->setCurrentUser($this->userRepository->getOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepository);
    }


    /** @dataProvider getDeleteUserEmails */
    public function testDeleteUserActionOnNonSelfExistingUser(string $userEmail): void
    {
        $user = $this->userRepository->getOneByEmail($userEmail);
        $userId = $user->getId();

        $deleteUserAction = new DeleteUserAction($this->userRepository);
        $deleteUserAction($this->getDeleteUserInput($userEmail));

        $this->assertNull($this->userRepository->getOneById($userId));
    }

    public function testDeleteUserActionOnSelfExistingUser(): void
    {
        $user = $this->userRepository->getOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL);
        $userId = $user->getId();

        $deleteUserAction = new DeleteUserAction($this->userRepository);
        $this->expectException(InvalidRequester::class);
        $deleteUserAction($this->getDeleteUserInput(DomainTestUserFixtures::ADMIN_EMAIL));

        self::assertNotNull($this->userRepository->getOneById($userId));
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
            $this->userRepository->getOneByEmail($userEmail),
        );
    }
}
