<?php

namespace App\Infrastructure\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class EnumField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/field/enumField.html.twig')
            ->setFormType(EnumType::class)
            ->setFormTypeOption('attr.class', 'width-inherit')
            ->setFormTypeOption('choice_label', static function (\BackedEnum $choice): string {
                return (string) $choice->value;
            });
    }

    public function setEnumClass(string $enumClass): self
    {
        $this->setFormTypeOption('class', $enumClass);

        return $this;
    }
}
