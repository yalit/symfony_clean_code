<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\User\Authorization\CreateUserAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Rule\UserUniqueEmailRule;
use App\Domain\User\Rule\UserUniqueEmailRuleValidator;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\ServiceFetcherInterface;
use App\Domain\Shared\Validation\Validator;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Action\CreateUserAction;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Service\Factory\UserFactory;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Tests\Domain\Shared\Authorization\TestAuthorizationChecker;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use App\Tests\Shared\Service\TestServiceFetcher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateUserActionTest extends TestCase
{
    private InMemoryTestUserRepository $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private UserFactory $userFactory;
    private ServiceFetcherInterface $serviceFetcher;
    private ValidatorInterface $validator;
    private AuthorizationCheckerInterface $authorizationChecker;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new InMemoryTestUserRepository();
        $this->passwordHasher = new TestPasswordHasher();
        $this->userFactory = new UserFactory($this->passwordHasher);
        $this->serviceFetcher = new TestServiceFetcher();
        $this->validator = new Validator($this->serviceFetcher);

        //load fixtures
        (new DomainTestUserFixtures($this->userRepository, $this->userFactory))->load();
        $this->userRepository->setCurrentUser($this->userRepository->getOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL));

        $this->serviceFetcher->addService(UserUniqueEmailRuleValidator::class, new UserUniqueEmailRuleValidator($this->userRepository));

        $this->authorizationChecker = new TestAuthorizationChecker();
        $this->authorizationChecker->addAuthorization(new CreateUserAuthorization($this->userRepository));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepository);
        unset($this->passwordHasher);
        unset($this->userFactory);
        unset($this->serviceFetcher);
        unset($this->validator);
        unset($this->authorizationChecker);
    }

    /**
     * @dataProvider getUserInput
     */
    public function testCreateUserAdminCommand(string $name, string $email, string $password, UserRole $role): void
    {
        $originalUserCount = count($this->userRepository->getAll());
        $commandInput = $this->getCreateUserInput([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->validator, $this->userFactory, $this->authorizationChecker);
        $command($commandInput);

        $users = $this->userRepository->getAll();
        self::assertCount($originalUserCount + 1, $users);
        $user = $this->userRepository->getOneByEmail($email);
        self::assertNotNull($user);
        self::assertInstanceOf(User::class, $user);
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($role, $user->getRole());

        self::assertNotEquals($password, $user->getPassword());
        self::assertTrue($this->passwordHasher->isPasswordValid($password, $user));
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function getUserInput(): iterable
    {
        yield 'Admin' => ['Test Admin', 'new_admin@email.com', 'Password123)', UserRole::ADMIN];
        yield 'Editor' => ['Test Editor', 'new_editor@email.com', 'Password123)', UserRole::EDITOR];
        yield 'Author' => ['Test Author', 'new_author@email.com', 'Password123)', UserRole::AUTHOR];
    }

    public function testCreateUserCommandWithoutRequesterShouldTriggerAnException(): void
    {
        $this->userRepository->setCurrentUser(null);
        $commandInput = $this->getCreateUserInput([
            'name' => 'Test Admin',
            'email' => 'admin@email.com',
            'password' => 'Password123)',
            'role' => UserRole::ADMIN,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->validator, $this->userFactory, $this->authorizationChecker);
        $this->expectException(InvalidRequester::class);
        $command($commandInput);
    }

    /**
     * @dataProvider getIncorrectRequesterEmail
     */
    public function testCreateUserCommandWithAnIncorrectRequesterShouldTriggerAnException(string $requesterEmail): void
    {
        $requester = $this->userRepository->getOneByEmail($requesterEmail);
        self::assertNotNull($requester);
        self::assertNotEquals(UserRole::ADMIN, $requester->getRole());

        $this->userRepository->setCurrentUser($requester);

        $commandInput = $this->getCreateUserInput([
            'name' => 'Test Admin',
            'email' => 'admin@email.com',
            'password' => 'Password123)',
            'role' => UserRole::ADMIN,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->validator, $this->userFactory, $this->authorizationChecker);
        $this->expectException(InvalidRequester::class);
        $command($commandInput);
    }

    /**
     * @return iterable<string, array<string>>
     */
    public function getIncorrectRequesterEmail(): iterable
    {
        yield "Editor" => [DomainTestUserFixtures::EDITOR_EMAIL];
        yield "Author" => [DomainTestUserFixtures::AUTHOR_EMAIL];
    }

    public function testCreateUserCommandWithExistingEmail(): void
    {
        $commandInput = $this->getCreateUserInput([
            'name' => 'Test Admin',
            'email' => 'admin@email.com',
            'password' => 'Password123)',
            'role' => UserRole::ADMIN,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->validator, $this->userFactory, $this->authorizationChecker);
        $this->expectException(ValidationException::class);
        $command($commandInput);

        self::assertStringContainsString(UserUniqueEmailRule::class, $this->getExpectedExceptionMessage());
        self::assertStringContainsString('Test Admin', $this->getExpectedExceptionMessage());
        self::assertStringContainsString('admin@email.com', $this->getExpectedExceptionMessage());
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getCreateUserInput(array $data): CreateUserInput
    {
        return new CreateUserInput(
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role'],
        );
    }
}
