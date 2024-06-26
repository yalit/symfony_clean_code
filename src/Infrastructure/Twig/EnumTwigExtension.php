<?php

namespace App\Infrastructure\Twig;

use BackedEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnumTwigExtension extends AbstractExtension
{
    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [new TwigFunction('enum_value', [$this, 'getEnumValue'])];
    }

    /**
     * @param class-string $enumClass
     */
    public function getEnumValue(string $enumClass, string $enumCase): string
    {
        try {
            $enum = constant(sprintf('%s::%s', $enumClass, $enumCase));
            if ($enum instanceof BackedEnum) {
                return $enum->value;
            }
            return $enum->name;
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('Invalid enum class or case');
        }
    }
}
