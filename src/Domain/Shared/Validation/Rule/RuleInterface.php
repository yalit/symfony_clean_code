<?php

namespace App\Domain\Shared\Validation\Rule;

interface RuleInterface
{
    public function getValidatorClass(): string;
    public function getErrorMessage(): string;
}
