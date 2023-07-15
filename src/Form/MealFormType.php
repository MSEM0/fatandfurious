<?php

namespace App\Form;

use App\Entity\Meal;
use ContainerBH74LRl\getForm_ChoiceListFactory_CachedService;
use phpDocumentor\Reflection\PseudoTypes\IntegerRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('kcal')
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
