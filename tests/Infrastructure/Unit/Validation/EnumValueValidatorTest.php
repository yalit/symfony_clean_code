<?php

namespace App\Tests\Infrastructure\Unit\Validation;

use App\Infrastructure\Validation\StringEnumValue;
use App\Infrastructure\Validation\StringEnumValueValidator;
use App\Tests\Shared\Enum\TestBackedStringEnum;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EnumValueValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidator
    {
        return new StringEnumValueValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new StringEnumValue(TestBackedStringEnum::class));

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new StringEnumValue(TestBackedStringEnum::class));

        $this->assertNoViolation();
    }

    public function testCorrectValueIsValid(): void
    {
        foreach (TestBackedStringEnum::cases() as $case) {
            $this->validator->validate($case->value, new StringEnumValue(TestBackedStringEnum::class));

            $this->assertNoViolation();
        }
    }

    public function testIncorrectStringValueRaiseViolation(): void
    {
        $this->validator->validate('incorrect_value', new StringEnumValue(TestBackedStringEnum::class));

        self::assertCount(1, $this->context->getViolations());
    }

    public function testNonStringValueRaiseViolation(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(1, new StringEnumValue(TestBackedStringEnum::class));

    }
}
