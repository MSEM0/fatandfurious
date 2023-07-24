<?php

namespace App\Controller;

use App\Form\DietFormType;
use App\Form\DietReadFormType;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietReadController extends AbstractController
{
    public function __construct(private readonly DietDataService $dietDataService)
    {
    }

    #[Route('/diet/read', name: 'app_diet_read')]
    public function index(Request $request): Response
    {
        $form = $this->createform(DietReadFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $startDate = $data['startDate']->format('d-m-Y');
            $endDate = $data['endDate']->format('d-m-Y');

            return $this->redirectToRoute('app_diet_read_choice', ['startDate' => $startDate, 'endDate' => $endDate]);
        }

        return $this->render('diet_read/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/diet/read/{startDate}%{endDate}', name: 'app_diet_read_choice')]
    public function viewSelectedDiet(Request $request)
    {   $totalKcalPerWeek = null;
        $selectedDiets = $this->dietDataService->getSelectedDiets($request);
        if (count($selectedDiets)>=7) {
            $totalKcalPerWeek = $this->dietDataService->getTotalKcal($selectedDiets, 7);
        }

        return $this->render('diet_read_choice/index.html.twig', ['selectedDiets' => $selectedDiets,
        'totalKcalPerWeek'=> $totalKcalPerWeek]);
    }
}
