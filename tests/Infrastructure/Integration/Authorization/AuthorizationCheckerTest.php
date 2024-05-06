<?php

namespace App\Tests\Infrastructure\Integration\Authorization;

use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Infrastructure\Authorization\DomainAuthorizationVoter;
use App\Tests\Shared\Authorization\TestAuthorization;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthorizationCheckerTest extends KernelTestCase
{
    private AuthorizationCheckerInterface $authorizationChecker;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->authorizationChecker = self::getContainer()->get(AuthorizationCheckerInterface::class);
        /** @var DomainAuthorizationVoter $domainAuthorizationVoter */
        $domainAuthorizationVoter = self::getContainer()->get(DomainAuthorizationVoter::class);
        $domainAuthorizationVoter->addDomainAuthorization(new TestAuthorization());
    }

    public function testIsAllowed(): void
    {
        self::assertTrue($this->authorizationChecker->allows(TestAuthorization::TEST_ACTION, TestAuthorization::TEST_RESOURCE));
    }

    public function testIsNotAllowedAsNoVoter(): void
    {
        self::assertFalse($this->authorizationChecker->allows('other_action', TestAuthorization::TEST_RESOURCE));
    }

    public function testIsNotAllowedAsIncorrectResource(): void
    {
        self::assertFalse($this->authorizationChecker->allows(TestAuthorization::TEST_ACTION, 'other_resource'));
    }
}
