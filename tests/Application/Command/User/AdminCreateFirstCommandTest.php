<?php

namespace App\Tests\Application\Command\User;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class AdminCreateFirstCommandTest extends KernelTestCase
{
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = static::getContainer()->get(UserRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->userRepository);
    }

    public function testExecuteWithAllNeededOptions(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        // ensure no user are in the database
        foreach ($this->userRepository->getAll() as $user) {
            $this->userRepository->delete($user->getId());
        }
        self::assertEmpty($this->userRepository->getAll());

        $command = $application->find('app:user:create-first-admin');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['yes']);
        $commandTester->execute([
            'name' => 'Wouter',
            'email' => 'admin@email.com',
            'password' => 'Password123)',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertCreateUserInDB('admin@email.com', 'Wouter');
    }

    public function testExecuteWithoutNameOption(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        // ensure no user are in the database
        foreach ($this->userRepository->getAll() as $user) {
            $this->userRepository->delete($user->getId());
        }
        self::assertEmpty($this->userRepository->getAll());

        $command = $application->find('app:user:create-first-admin');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['Wouter', 'yes']);
        $commandTester->execute([
            'email' => 'admin@email.com',
            'password' => 'Password123)',
        ]);

        $commandTester->assertCommandIsSuccessful();
        $this->assertCreateUserInDB('admin@email.com', 'Wouter');
    }

    public function testExecuteWhileUsersAreExisting(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('app:user:create-first-admin');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['yes']);
        $commandTester->execute([
            'name' => 'Wouter',
            'email' => 'newadmin@email.com',
            'password' => 'Password123)',
        ]);

        self::assertEquals(Command::FAILURE, $commandTester->getStatusCode());
        self::assertNull($this->userRepository->getOneByEmail('newadmin@email.com'));
    }

    private function assertCreateUserInDB(string $email, string $name): void
    {
        $user = $this->userRepository->getOneByEmail($email);
        self::assertNotNull($user);
        self::assertEquals($name, $user->getName());
        self::assertEquals(UserRole::ADMIN, $user->getRole());
    }
}
