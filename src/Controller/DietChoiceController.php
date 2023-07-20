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

use function PHPUnit\Framework\throwException;


class DietChoiceController extends AbstractController
{
    public function __construct(private readonly DietChoiceService $dietChoiceService, private readonly DietDataService $dietDataService)
    {
    }

    #[Route('/diet/{mealSets}?{date}', name: 'app_diet_choice')]
    public function showMealSets(Request $request): Response
    {
        $validSets = $this->dietChoiceService->getRandomSetsOfMeals($request);
        $request->getSession()->getFlashBag()->set('validSets', $validSets);
        return $this->render('diet_choice/index.html.twig', ['sets' => $validSets]);
    }

    #[Route('/save-diet', name: 'app_save_diet', methods: ["POST"])]
    public function userDietSave(Request $request){
        $validSets = $request->getSession()->getFlashBag()->get('validSets');
        if ($validSets !== null){
            $this->dietDataService->saveDiet($validSets);
//            return new Response('Diet saved in database.');
           return new RedirectResponse('diet' . '?message=Diet%20saved%20in%20database.');
        }
        else
        {return new Response('Something went wrong.');}
    }

}
