<?php

namespace App\Tests\Infrastructure\Unit\Validation;

use App\Domain\Shared\ServiceFetcherInterface;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Infrastructure\Doctrine\Mapper\DoctrineUserMapper;
use App\Infrastructure\Doctrine\Model\DoctrineUser;
use App\Infrastructure\Validation\DomainSpecificationConstraint;
use App\Infrastructure\Validation\DomainSpecificationConstraintValidator;
use App\Tests\Shared\Service\TestServiceFetcher;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/** @template-extends ConstraintValidatorTestCase<DomainSpecificationConstraintValidator> */
class DomainSpecificationConstraintValidatorTest extends ConstraintValidatorTestCase
{
    private ServiceFetcherInterface $serviceFetcher;
    private ValidatorInterface&MockObject $domainValidator;

    protected function setUp(): void
    {
        $this->serviceFetcher = new TestServiceFetcher();
        $this->serviceFetcher->addService(DoctrineUserMapper::class, new DoctrineUserMapper());
        parent::setUp();
    }

    /**
     * @inheritDoc
     */
    protected function createValidator(): DomainSpecificationConstraintValidator
    {
        $this->domainValidator = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        return new DomainSpecificationConstraintValidator($this->serviceFetcher, $this->domainValidator);
    }

    public function testValidateWithValidDto(): void
    {
        $this->domainValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $doctrineUser = new DoctrineUser();
        $doctrineUser->setName('test name');
        $doctrineUser->setEmail('test@email.com');
        $doctrineUser->setRole(UserRole::AUTHOR);
        $doctrineUser->setPlainPassword('Password123)');

        $this->validator->validate($doctrineUser, new DomainSpecificationConstraint(CreateUserInput::class, DoctrineUserMapper::class));
        $this->assertNoViolation();
    }

    public function testValidateWithInvalidDto(): void
    {
        $this->domainValidator
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->domainValidator
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn([
                new class () {
                    public function getMessage(): string
                    {
                        return 'error message';
                    }

                    public function getRuleName(): string
                    {
                        return 'rule name';
                    }
                },
            ]);

        $doctrineUser = new DoctrineUser();
        $doctrineUser->setName('test name');
        $doctrineUser->setEmail('test@email.com');
        $doctrineUser->setRole(UserRole::AUTHOR);
        $doctrineUser->setPlainPassword('Password123)');

        $this->validator->validate($doctrineUser, new DomainSpecificationConstraint(CreateUserInput::class, DoctrineUserMapper::class));
        $violations = $this->context->getViolations();
        self::assertCount(1, $violations);
    }
}
