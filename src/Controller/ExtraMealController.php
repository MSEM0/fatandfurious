<?php

namespace App\Controller;

use App\Form\ExtraMealFormType;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtraMealController extends AbstractController
{
    public function __construct(private readonly DietDataService $dietDataService)
    {
    }

    #[Route('/diet/extra-meal', name: 'app_extra_meal')]
    public function index(Request $request): Response
    {
        $form = $this->createform(ExtraMealFormType::class);
        $form->handleRequest($request);

        $success = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $date = $data['date']->format('d-m-Y');
            $extraMeals = $data['extraMeals'];
            $this->dietDataService->extraMealsUpdate($extraMeals, $date);
            $success = true;
            $form = $this->createForm(ExtraMealFormType::class);

            return $this->render('extra_meal/index.html.twig', [
                'form' => $form->createView(),
                'success' => $success
            ]);
        }
        return $this->render('extra_meal/index.html.twig', [
            'form' => $form->createView(),
            'success' => $success
        ]);
    }
}
