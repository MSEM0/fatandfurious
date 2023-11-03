<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\DietReadFormType;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function viewSelectedDiet(Request $request): Response
    {
        $startDate = $request->attributes->get('startDate');
        $endDate = $request->attributes->get('endDate');
        $selectedDiets = $this->dietDataService->getSelectedDiets($request);

        if ($request->query->get('download') === 'doc') {
            return $this->dietDataService->downloadDiet($selectedDiets);
        }

        $period = (count($selectedDiets));
        $totalKcalPerChosenPeriod = $this->dietDataService->getTotalKcal($selectedDiets, $period);

        return $this->render('diet_read_choice/index.html.twig', [
            'selectedDiets' => $selectedDiets,
            'totalKcalPerChosenPeriod' => $totalKcalPerChosenPeriod,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
