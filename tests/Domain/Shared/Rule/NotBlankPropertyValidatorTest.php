<?php

namespace App\Tests\Domain\Shared\Rule;

use App\Domain\Shared\Rule\NotBlankPropertyValidator;
use App\Domain\Shared\Rule\NotBlankProperty;
use PHPUnit\Framework\TestCase;

class NotBlankPropertyValidatorTest extends TestCase
{
    /** @dataProvider getTestClassObjectDataProvider */
    public function testIsSatisfiedByWithCorrectProperty(string $propertyName, ?string $value): void
    {
        $object = $this->getTestClassObject();
        $setter = 'set' . ucfirst($propertyName);
        $object->$setter($value);
        $rule = new NotBlankProperty($propertyName);
        $validator = new NotBlankPropertyValidator();
        self::assertTrue($validator->isValid($object, $rule));
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getTestClassObjectDataProvider(): array
    {
        return [
            ['propertyName' => 'name', 'value' => 'test'],
            ['propertyName' => 'name', 'value' => 'test'],
            ['propertyName' => 'other', 'value' => 'test'],
            ['propertyName' => 'other', 'value' => 'test'],
        ];
    }

    public function testIsSatisfiedByWithNonExistingProperty(): void
    {
        $object = $this->getTestClassObject();
        $rule = new NotBlankProperty('title');
        $validator = new NotBlankPropertyValidator();
        self::assertTrue($validator->isValid($object, $rule));
    }

    private function getTestClassObject(): object
    {
        return new class {
            public function __construct(
                private ?string $name = '',
                private ?string $other = ''
            ) {
            }

            public function getName(): ?string
            {
                return $this->name;
            }

            public function getOther(): ?string
            {
                return $this->other;
            }

            public function setName(?string $name): void
            {
                $this->name = $name;
            }

            public function setOther(?string $other): void
            {
                $this->other = $other;
            }
        };
    }
}
