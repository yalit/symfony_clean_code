<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Validator;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Action\EditUserAction;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Authorization\EditUserAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Rule\UserUniqueDataEmailRuleValidator;
use App\Domain\User\Service\Factory\UserFactory;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Tests\Domain\Shared\Authorization\TestAuthorizationChecker;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use App\Tests\Shared\Service\TestServiceFetcher;
use PHPUnit\Framework\TestCase;

class EditUserActionTest extends TestCase
{
    private InMemoryTestUserRepository $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function setUp(): void
    {
        $this->userRepository = new InMemoryTestUserRepository();
        $this->passwordHasher = new TestPasswordHasher();

        (new DomainTestUserFixtures($this->userRepository, new UserFactory($this->passwordHasher)))->load();
        $this->userRepository->setCurrentUser($this->userRepository->findOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL));

        $serviceFetcher = new TestServiceFetcher();
        $serviceFetcher->addService(UserUniqueDataEmailRuleValidator::class, new UserUniqueDataEmailRuleValidator($this->userRepository));
        $this->validator = new Validator($serviceFetcher);

        $this->authorizationChecker = new TestAuthorizationChecker();
        $this->authorizationChecker->addAuthorization(new EditUserAuthorization($this->userRepository));
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
    public function testEditUserActionWithNoPasswordUpdate(string $userEmail, array $data): void
    {
        $user = $this->userRepository->findOneByEmail($userEmail);

        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);
        $editUserAction($this->getEditUserInput($userEmail, $data));
        $updatedUser = $this->userRepository->findOneById($user->getId());
        foreach ($data as $key => $value) {
            $getter = 'get' . ucfirst($key);
            $this->assertEquals($value, $updatedUser->$getter());
        }
    }

    /**
     * @dataProvider getUserEditData
     * @param array<string, mixed> $data
     */
    public function testEditUserActionByItselfWithNoPasswordUpdate(string $userEmail, array $data): void
    {
        $user = $this->userRepository->findOneByEmail($userEmail);
        self::assertNotNull($user);

        $this->userRepository->setCurrentUser($user);

        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);
        $editUserAction($this->getEditUserInput($userEmail, $data));

        $updatedUser = $this->userRepository->findOneById($user->getId());
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

    public function testEditUseActionWithPasswordUpdate(): void
    {
        $user = $this->userRepository->findOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        $newPassword = 'newPassword';
        $oldPassword = $user->getPassword();
        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, [], $newPassword));

        $updatedUser = $this->userRepository->findOneById($user->getId());
        self::assertNotEquals($oldPassword, $updatedUser->getPassword());
        self::assertNotEquals($newPassword, $updatedUser->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid($newPassword, $updatedUser));
    }

    public function testEditUserOnANonExistingData(): void
    {
        $user = $this->userRepository->findOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['non_existing_data' => 'notChanged']));
        $updateUser = $this->userRepository->findOneById($user->getId());
        self::assertNotEquals('notChanged', $updateUser->getName());
        self::assertNotEquals('notChanged', $updateUser->getEmail());
    }

    public function testEditUserByNonAdminAndNonItself(): void
    {
        $this->userRepository->setCurrentUser($this->userRepository->findOneByEmail(DomainTestUserFixtures::AUTHOR_EMAIL));

        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);

        $this->expectException(InvalidRequester::class);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['name' => 'New Editor Name']));

        $updateUser = $this->userRepository->findOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        self::assertNotEquals('New Editor Name', $updateUser->getName());
    }

    public function testEditUserByNoUser(): void
    {
        $this->userRepository->setCurrentUser(null);

        $editUserAction = new EditUserAction($this->userRepository, $this->passwordHasher, $this->validator, $this->authorizationChecker);

        $this->expectException(InvalidRequester::class);
        $editUserAction($this->getEditUserInput(DomainTestUserFixtures::EDITOR_EMAIL, ['name' => 'New Editor Name']));

        $updateUser = $this->userRepository->findOneByEmail(DomainTestUserFixtures::EDITOR_EMAIL);
        self::assertNotEquals('New Editor Name', $updateUser->getName());
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getEditUserInput(string $userEmail, array $data, ?string $newPassword = null): EditUserInput
    {
        return new EditUserInput(
            $this->userRepository->findOneByEmail($userEmail),
            $data,
            $newPassword,
        );
    }
}
