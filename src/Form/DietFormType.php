<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DietFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mealSets', IntegerType::class, [
                'label' => 'From 1 to 10: ',
                'attr' => ['min' => 1, 'max' => 10],
            ])
            ->add('date', DateType::class, [
                'label' => 'First day: ',
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, ['label' => ' Spin the wheel of fat whores ']);
    }
}