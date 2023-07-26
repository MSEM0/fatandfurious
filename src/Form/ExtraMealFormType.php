<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ExtraMealFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('extraMeals', IntegerType::class, [
                'label' => 'Total kcal of extra meals: ',
                'required' => false,
                'empty_data' => null,

            ])
            ->add('date', DateType::class, [
                'label' => 'Cheat date: ',
                'widget' => 'single_text',

            ])
            ->add('submit', SubmitType::class, [
                'label' => ' Add extra meals kcal '
            ])
            ->add('delete', SubmitType::class, [
                'label' => "Delete selected day's value",
            ]);
    }
}
