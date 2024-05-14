<?php

namespace App\Domain\Shared\Validation;

use Egulias\EmailValidator\Result\MultipleErrors;

interface ValidatorInterface
{
    public function isValid(object $object): bool;

    /** @return ValidatorError[] */
    public function getErrors(): array;
}
