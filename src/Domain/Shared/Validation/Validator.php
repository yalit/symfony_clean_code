<?php

namespace App\Domain\Shared\Validation;

use App\Domain\Shared\ServiceFetcherInterface;
use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\ValidatorInterface;
use InvalidArgumentException;

class Validator implements ValidatorInterface
{
    public function __construct(private readonly ServiceFetcherInterface $serviceFetcher) {}

    public function isValid(object $object): bool
    {
        $rules = $this->getObjectRules($object);

        foreach ($rules as $rule) {
            $validator = $this->serviceFetcher->fetch($rule->getValidatorClass());
            if (!$validator) {
                throw new InvalidArgumentException('Validator not found');
            }

            if (!$validator->isValid($object, $rule)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return RuleInterface[]
     */
    private function getObjectRules(object $object): array
    {
        $rules = [];

        $reflection = new \ReflectionClass($object);
        $attributes = $reflection->getAttributes();
        foreach ($attributes as $attribute) {
            $rule = $attribute->newInstance();
            if (!$rule instanceof RuleInterface) {
                continue;
            }
            $rules[] = $rule;
        }

        return $rules;
    }
}
