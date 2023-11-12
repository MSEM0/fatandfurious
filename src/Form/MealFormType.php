<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class MealFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('kcal')
            ->add('fats')
            ->add('carbons')
            ->add('proteins')
            ->add('satisfaction', ChoiceType::class, [
                'choices' =>
                    [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => ['breakfast' => 'breakfast', 'dinner' => 'dinner', 'supper' => 'supper', 'all' => 'all']
            ])
            ->add('ingredients')
            ->add('doublePortion');
    }
}
