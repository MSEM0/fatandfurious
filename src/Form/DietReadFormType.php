<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class DietReadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'First day: ',
                'widget' => 'single_text',
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Last day: ',
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, ['label' => ' View diet data ']);
        ;
    }

}
