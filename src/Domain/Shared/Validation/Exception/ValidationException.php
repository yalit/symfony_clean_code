<?php

namespace App\Domain\Shared\Validation\Exception;

use App\Domain\Shared\Validation\ValidatorError;
use Exception;

class ValidationException extends Exception
{
    /**
     * @param ValidatorError[] $errors
     */
    public function __construct(private readonly array $errors, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Validation error found. Check the validatorResult', $code, $previous);
    }

    /**
     * @return ValidatorError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
