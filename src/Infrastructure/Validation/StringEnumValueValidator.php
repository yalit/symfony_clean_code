<?php

namespace App\Infrastructure\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class StringEnumValueValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof StringEnumValue) {
            throw new UnexpectedTypeException($constraint, StringEnumValue::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!enum_exists($constraint->getEnumClass())) {
            throw new UnexpectedValueException($constraint->getEnumClass(), 'className');
        }

        if (!in_array($value, array_column($constraint->getEnumClass()::cases(), 'value'), true)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
