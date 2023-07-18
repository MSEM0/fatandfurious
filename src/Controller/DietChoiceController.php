<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\DietChoiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DietChoiceController extends AbstractController
{
    public function __construct(private readonly DietChoiceService $dietChoiceService)
    {
    }

    #[Route('/diet/{mealSets}', name: 'app_diet_choice')]
    public function showMealSets(Request $request): Response
    {
        $validSets = $this->dietChoiceService->getRandomSetsOfMeals($request);

        return $this->render('diet_choice/index.html.twig', ['sets' => $validSets]);
    }
}
