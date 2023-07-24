<?php

namespace App\Form;

use App\Entity\Diet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraMealFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('extraMeals', IntegerType::class, [
                'label' => 'Total kcal of extra meals: '
            ])
            ->add('date', DateType::class, [
                'label' => 'Cheat date: ',
                'widget' => 'single_text',
            ])
        ->add('submit', SubmitType::class, [
            'label' => ' Add extra meals kcal ']);

    }
}
