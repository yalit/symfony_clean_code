<?php

namespace App\Domain\Shared\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class NotBlankProperty implements RuleInterface
{
    public function __construct(private readonly string $propertyName)
    {
    }

    public function getValidatorClass(): string
    {
        return NotBlankPropertyValidator::class;
    }

    public function getErrorMessage(): string
    {
        return 'Name cannot be blank';
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}
