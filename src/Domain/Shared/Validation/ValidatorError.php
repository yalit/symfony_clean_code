<?php

namespace App\Domain\Shared\Validation;

class ValidatorError
{
    /**
     * @param class-string $ruleName
     */
    public function __construct(
        private readonly string $ruleName,
        private readonly string $message
    ) {}

    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
