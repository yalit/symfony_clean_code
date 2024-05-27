<?php

namespace App\Tests\Domain\Shared\Action;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\ServiceFetcherInterface;
use App\Domain\Shared\Validation\Validator;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\Factory\UserFactory;
use App\Tests\Domain\Shared\Authorization\TestAuthorizationChecker;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;
use App\Tests\Domain\User\Repository\InMemoryTestUserRepository;
use App\Tests\Domain\User\Service\TestPasswordHasher;
use App\Tests\Shared\Service\TestServiceFetcher;
use PHPUnit\Framework\TestCase;

class DomainActionTestCase extends TestCase
{
    protected TestServiceFetcher $serviceFetcher;
    protected ValidatorInterface $validator;
    protected TestAuthorizationChecker $authorizationChecker;
    protected UserFactory $userFactory;
    protected InMemoryTestUserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->serviceFetcher = new TestServiceFetcher();
        $this->validator = new Validator($this->serviceFetcher);
        $this->authorizationChecker = new TestAuthorizationChecker();
        $this->userFactory = new UserFactory(new TestPasswordHasher());
        $this->userRepository = new InMemoryTestUserRepository();

        //load fixtures
        (new DomainTestUserFixtures($this->userRepository, $this->userFactory))->load();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->serviceFetcher);
        unset($this->validator);
        unset($this->authorizationChecker);
    }

    protected function setCurrentUser(?string $email = DomainTestUserFixtures::ADMIN_EMAIL): void
    {
        if ($email === null) {
            $this->userRepository->setCurrentUser(null);
            return;
        }

        $this->userRepository->setCurrentUser($this->userRepository->getOneByEmail($email));
    }

}
