<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietController extends AbstractController
{
    #[Route('/diet', name: 'app_diet')]
    public function dietForm(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('mealSets', IntegerType::class, [
                'label' => 'From 1 to 10:',
                'attr' => ['min' => 1, 'max' => 10],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Spin the wheel of fat whores'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mealSets = $data['mealSets'];

            return $this->redirectToRoute('app_diet_choice', ['mealSets' => $mealSets]);
        }

        return $this->render('diet/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}