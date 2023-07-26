<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\DietChoiceService;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietChoiceController extends AbstractController
{
    public function __construct(
        private readonly DietChoiceService $dietChoiceService,
        private readonly DietDataService $dietDataService
    ) {
    }

    #[Route('/diet/{mealSets}?{date}', name: 'app_diet_choice')]
    public function showMealSets(Request $request): Response
    {
        $validSets = $this->dietChoiceService->getRandomSetsOfMeals($request);
        $hasDuplicatedDiets = $this->dietDataService->hasDuplicatedDiets($validSets);
        $request->getSession()->getFlashBag()->set('validSets', $validSets);
        return $this->render(
            'diet_choice/index.html.twig',
            ['sets' => $validSets, 'hasDuplicatedDiets' => $hasDuplicatedDiets]
        );
    }

    #[Route('/save-diet', name: 'app_save_diet', methods: ["POST"])]
    public function userDietSave(Request $request)
    {
        $validSets = $request->getSession()->getFlashBag()->get('validSets');
        if ($validSets !== null) {
            $this->dietDataService->saveDiet($validSets);

            return new RedirectResponse('diet' . '?msg-sav=Diet%20saved%20in%20database.');
        } else {
            return new Response('Something went wrong.');
        }
    }

}
