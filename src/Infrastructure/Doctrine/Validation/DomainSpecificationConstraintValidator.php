<?php

namespace App\Infrastructure\Doctrine\Validation;

use App\Domain\Shared\ServiceFetcherInterface;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Infrastructure\Doctrine\Mapper\DoctrineMapperInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DomainSpecificationConstraintValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ServiceFetcherInterface $serviceFetcher,
        private readonly ValidatorInterface      $validator,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof DomainSpecificationConstraint) {
            throw new UnexpectedTypeException($constraint, DomainSpecificationConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if(!is_object($value)) {
            throw new UnexpectedTypeException($value, 'object');
        }

        try {
            /** @var DoctrineMapperInterface $mapper */
            $mapper = $this->serviceFetcher->fetch($constraint->getMapperToDtoClassName());
            $dto = $mapper->toDomainDto($value, $constraint->getDtoClassName());
        } catch (\ReflectionException $e) {
            throw new UnexpectedTypeException($value, $constraint->getDtoClassName());
        }

        if (!$this->validator->isValid($dto)) {
            $this->context->buildViolation('Error in the input data')
                ->addViolation();
        }
    }
}
