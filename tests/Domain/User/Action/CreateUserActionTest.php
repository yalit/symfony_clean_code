<?php

namespace App\Tests\Domain\User\Action;

use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Exception\InvalidSpecification;
use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use App\Domain\User\Action\CreateUserAction;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Service\Factory\UserFactory;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Domain\User\Specification\UserUniqueEmailSpecification;
use App\Tests\Domain\Shared\Specification\TestSpecificationVerifier;
use App\Tests\Domain\User\Repository\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use PHPUnit\Framework\TestCase;

class CreateUserActionTest extends TestCase
{
    private SpecificationVerifierInterface $specificationVerifier;
    private InMemoryTestUserRepository $userRepository;
    private PasswordHasherInterface $passwordHasher;
    private UserFactory $userFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->specificationVerifier = new TestSpecificationVerifier();
        $this->userRepository = new InMemoryTestUserRepository();
        $this->passwordHasher = new TestPasswordHasher();
        $this->userFactory = new UserFactory($this->passwordHasher);

        //load fixtures
        (new DomainTestUserFixtures($this->userRepository, $this->userFactory))->load();
        $this->userRepository->setCurrentUser($this->userRepository->findOneByEmail(DomainTestUserFixtures::ADMIN_EMAIL));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepository);
        unset($this->specificationVerifier);
    }

    /**
     * @dataProvider getUserInput
     */
    public function testCreateUserAdminCommand(string $name, string $email, string $password, UserRole $role): void
    {
        $originalUserCount = count($this->userRepository->findAll());
        $commandInput = $this->getCreateUserInput([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->specificationVerifier, $this->userFactory);
        $command($commandInput);

        $users = $this->userRepository->findAll();
        self::assertCount($originalUserCount + 1, $users);
        $user = $this->userRepository->findOneByEmail($email);
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

        $command = new CreateUserAction($this->userRepository, $this->specificationVerifier, $this->userFactory);
        $this->expectException(InvalidRequester::class);
        $command($commandInput);
    }

    /**
     * @dataProvider getIncorrectRequesterEmail
     */
    public function testCreateUserCommandWithAnIncorrectRequesterShouldTriggerAnException(string $requesterEmail): void
    {
        $requester = $this->userRepository->findOneByEmail($requesterEmail);
        self::assertNotNull($requester);
        self::assertNotEquals(UserRole::ADMIN, $requester->getRole());

        $this->userRepository->setCurrentUser($requester);

        $commandInput = $this->getCreateUserInput([
            'name' => 'Test Admin',
            'email' => 'admin@email.com',
            'password' => 'Password123)',
            'role' => UserRole::ADMIN,
        ]);

        $command = new CreateUserAction($this->userRepository, $this->specificationVerifier, $this->userFactory);
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

        $command = new CreateUserAction($this->userRepository, $this->specificationVerifier, $this->userFactory);
        $this->expectException(InvalidSpecification::class);
        $command($commandInput);

        self::assertStringContainsString(UserUniqueEmailSpecification::class, $this->getExpectedExceptionMessage());
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
