<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ExtraMealFormType;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $date = $data['date']->format('d-m-Y');

            if ($form->getClickedButton()->getName() === 'delete') {

                $this->dietDataService->deleteExtraMeals($date);

                return $this->render('extra_meal/index.html.twig', [
                    'form' => $form->createView(),
                    'delete' => true,
                    'success' => false
                ]);
            }
            $extraMeals = $data['extraMeals'];
            $extraMealsP = $data['extraMealsP'];
            $extraMealsC = $data['extraMealsC'];
            $extraMealsF = $data['extraMealsF'];
            $extraMealsComment = $data['extraMealsComment'];
            $this->dietDataService->extraMealsUpdate($extraMeals, $date, $extraMealsP, $extraMealsC, $extraMealsF, $extraMealsComment);
            $form = $this->createForm(ExtraMealFormType::class);


            return $this->render('extra_meal/index.html.twig', [
                'form' => $form->createView(),
                'success' => true,
                'delete' => false
            ]);
        }
        return $this->render('extra_meal/index.html.twig', [
            'form' => $form->createView(),
            'success' => false,
            'delete' => false
        ]);
    }
}
