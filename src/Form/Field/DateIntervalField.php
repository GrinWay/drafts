<?php

namespace App\Form\Field;

use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Component\Form\Extension\Core\Type;

class DateIntervalField
{
	use FieldTrait;

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplatePath('admin/crud/field/dateinterval.html.twig')
            ->setFormType(Type\DateIntervalType::class)
            ->setDefaultColumns('col-md-6 col-xxl-5')
		;
    }
}