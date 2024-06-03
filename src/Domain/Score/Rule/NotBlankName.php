<?php

namespace App\Domain\Score\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use Attribute;

#[Attribute]
class NotBlankName implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return NotBlankNameValidator::class;
    }

    public function getErrorMessage(): string
    {
        return 'Name cannot be blank';
    }
}
