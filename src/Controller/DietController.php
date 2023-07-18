<?php

namespace App\Controller;

use App\Form\DietFormType;
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
        $form = $this->createform(DietFormType::class);
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