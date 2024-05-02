<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Action\EditUserAction;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use PHPUnit\Framework\TestCase;

class EditUserActionTest extends TestCase
{
    private InMemoryTestUserRepository $userRepository;

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

    /**
     * @dataProvider getUserEditData
     * @param array<string, mixed> $data
     */
    public function testEditUserAction(string $userEmail, array $data): void
    {
        $user = $this->userRepository->getOneByEmail($userEmail);

        $editUserAction = new EditUserAction($this->userRepository);
        $editUserAction($this->getEditUserInput($userEmail, $data));
        $updatedUser = $this->userRepository->getOneById($user->getId());
        foreach ($data as $key => $value) {
            $getter = 'get' . ucfirst($key);
            $this->assertEquals($value, $updatedUser->$getter());
        }
    }

    /**
     * @dataProvider getUserEditData
     * @param array<string, mixed> $data
     */
    public function testEditUserActionByItself(string $userEmail, array $data): void
    {
        $user = $this->userRepository->getOneByEmail($userEmail);
        self::assertNotNull($user);

        $this->userRepository->setCurrentUser($user);

        $editUserAction = new EditUserAction($this->userRepository);
        $editUserAction($this->getEditUserInput($userEmail, $data));

        $updatedUser = $this->userRepository->getOneById($user->getId());
        foreach ($data as $key => $value) {
            $getter = 'get' . ucfirst($key);
            $this->assertEquals($value, $updatedUser->$getter());
        }
    }

    /**
     * @return iterable<string, array<string, mixed>>
     */
    public function getUserEditData(): iterable
    {
        yield 'Single email' => ['userEmail' => DomainTestUserFixtures::EDITOR_EMAIL, 'data' => ['email' => 'newEmail@email.com']];
        yield 'Single name' => ['userEmail' => DomainTestUserFixtures::EDITOR_EMAIL, 'data' => ['name' => 'New Editor Name']];
        yield 'Multiple data' => ['userEmail' => DomainTestUserFixtures::EDITOR_EMAIL, 'data' => ['email' => 'newEmail@email.com', 'name' => 'New Editor Name']];
    }

    public function testEditUserOnANonExistingData(): void
    {
        $user = $this->userRepository->getOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        $editUserAction = new EditUserAction($this->userRepository);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['non_existing_data' => 'notChanged']));
        $updateUser = $this->userRepository->getOneById($user->getId());
        self::assertNotEquals('notChanged', $updateUser->getName());
        self::assertNotEquals('notChanged', $updateUser->getEmail());
    }

    public function testEditUserByNonAdminAndNonItself(): void
    {
        $this->userRepository->setCurrentUser($this->userRepository->getOneByEmail(DomainTestUserFixtures::AUTHOR_EMAIL));

        $editUserAction = new EditUserAction($this->userRepository);

        $this->expectException(InvalidRequester::class);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['name' => 'New Editor Name']));

        $updateUser = $this->userRepository->getOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        self::assertNotEquals('New Editor Name', $updateUser->getName());
    }

    public function testEditUserByNoUser(): void
    {
        $this->userRepository->setCurrentUser(null);

        $editUserAction = new EditUserAction($this->userRepository);

        $this->expectException(InvalidRequester::class);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['name' => 'New Editor Name']));

        $updateUser = $this->userRepository->getOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        self::assertNotEquals('New Editor Name', $updateUser->getName());
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getEditUserInput(string $userEmail, array $data): EditUserInput
    {
        return new EditUserInput(
            $this->userRepository->getOneByEmail($userEmail),
            $data,
        );
    }
}
