<?php

namespace App\Infrastructure\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class StringEnumValue extends Constraint
{
    public string $message;

    public function __construct(private readonly string $enumClass, mixed $options = null, array $groups = null, mixed $payload = null)
    {
        $this->message = sprintf('The value you selected is not a valid choice in %s', $this->enumClass);
        parent::__construct($options, $groups, $payload);
    }

    public function getEnumClass(): string
    {
        return $this->enumClass;
    }
}
