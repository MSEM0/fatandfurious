<?php

namespace App\Services;

use App\Enum\Meals;
use App\Repository\MealRepository;
use Symfony\Component\HttpFoundation\Request;

class DietChoiceService
{
    public function __construct(private readonly MealRepository $mealRepository)
    {
    }

//Gets randomly meal sets (breakfast, dinner and supper) in number of sets, calories and satisfaction picked by the user
    public function getRandomSetsOfMeals(Request $request): array
    {
        $startDate = $request->attributes->get('date');
        $mealSets = intval($request->attributes->get('mealSets'));
        $i = 1;
        $a = $mealSets - 1;
        $userMinKcal = 1500;
        $userMaxKcal = 1700;
        $userMedSatisfaction = 3;

        while ($i <= $mealSets) {
            $criteria1 = [
                'type' => ['all', Meals::BRK]
            ];
            $criteria2 = [
                'type' => ['all', Meals::DNR]
            ];
            $criteria3 = [
                'type' => ['all', Meals::SPR],
            ];

            ${Meals::R_BRK} = $this->mealRepository->getRandomMeal(Meals::BRK, $criteria1, $i, $mealSets);

            ${Meals::R_DNR} = $this->mealRepository->getRandomMeal(Meals::DNR, $criteria2, $i, $mealSets);

            ${Meals::R_SPR} = $this->mealRepository->getRandomMeal(Meals::SPR, $criteria3, $i, $mealSets);

            $totalKcal = ${Meals::R_BRK}['kcal'] + ${Meals::R_DNR}['kcal'] + ${Meals::R_SPR}['kcal'];
            $medSatisfaction = (${Meals::R_BRK}['satisfaction'] + ${Meals::R_DNR}['satisfaction'] + ${Meals::R_SPR}['satisfaction']) / 3;


            if (${Meals::R_DNR} != null && $totalKcal > $userMinKcal && $totalKcal < $userMaxKcal && $medSatisfaction > $userMedSatisfaction) {
                if ($i === 1) {
                    $date = $startDate;
                } else {
                    $date = (new \DateTime($startDate))->modify("+$i day -1 day")->format('d-m-Y');
                }
                $i++;
                $validSets[] = [
                    Meals::R_BRK => ${Meals::R_BRK},
                    Meals::R_DNR => ${Meals::R_DNR},
                    Meals::R_SPR => ${Meals::R_SPR},
                    'kcal' => $totalKcal,
                    'satisfaction' => $medSatisfaction,
                    'date' => $date
                ];
            }
        }
//When user pick at least 2 sets, it checks if a meal is prepared as a double portion, if so, then it changes the next day's meal for the previous one
//Also avoids overwriting more than one meal
        if ($i >= 3) {
            for ($r = 3; $r <= $i; $r++) {
                if ($r == 3 && $validSets[$r - 3][Meals::R_BRK]['doublePortion'] === true) {
                    $validSets[$r - 2][Meals::R_BRK] = $validSets[$r - 3][Meals::R_BRK];
                } elseif ($validSets[$r - 3][Meals::R_BRK]['doublePortion'] === true && $validSets[$r - 3][Meals::R_BRK] != $validSets[$r - 4][Meals::R_BRK]) {
                    $validSets[$r - 2][Meals::R_BRK] = $validSets[$r - 3][Meals::R_BRK];
                }
                if ($r == 3 && $validSets[$r - 3][Meals::R_DNR]['doublePortion'] === true) {
                    $validSets[$r - 2][Meals::R_DNR] = $validSets[$r - 3][Meals::R_DNR];
                } elseif ($validSets[$r - 3][Meals::R_DNR]['doublePortion'] === true && $validSets[$r - 3][Meals::R_DNR] != $validSets[$r - 4][Meals::R_DNR]) {
                    $validSets[$r - 2][Meals::R_DNR] = $validSets[$r - 3][Meals::R_DNR];
                }
                if ($r == 3 && $validSets[$r - 3][Meals::R_SPR]['doublePortion'] === true) {
                    $validSets[$r - 2][Meals::R_SPR] = $validSets[$r - 3][Meals::R_SPR];
                } elseif ($validSets[$r - 3][Meals::R_SPR]['doublePortion'] === true && $validSets[$r - 3][Meals::R_SPR] != $validSets[$r - 4][Meals::R_SPR]) {
                    $validSets[$r - 2][Meals::R_SPR] = $validSets[$r - 3][Meals::R_SPR];
                }
            }
        }
//After changes in meal sets because of double portions it calculates again the calories and satisfaction, if they won't match user's requirements, the script runs again for better outcome
        while ($a <= ($mealSets - 1) && $a >= 0) {
            $totalKcalFixed = $validSets[$a][Meals::R_BRK]['kcal'] + $validSets[$a][Meals::R_DNR]['kcal'] + $validSets[$a][Meals::R_SPR]['kcal'];
            $medSatisfactionFixed = ($validSets[$a][Meals::R_BRK]['satisfaction'] + $validSets[$a][Meals::R_DNR]['satisfaction'] + $validSets[$a][Meals::R_SPR]['satisfaction']) / 3;
            $validSets[$a]['kcal'] = $totalKcalFixed;
            $validSets[$a]['satisfaction'] = $medSatisfactionFixed;
            if (($totalKcalFixed < $userMinKcal || $totalKcalFixed > $userMaxKcal) || ($medSatisfactionFixed < $userMedSatisfaction)) {
                return $this->getRandomSetsOfMeals($request);
            }
            $a--;
        }
        return $validSets;
    }
}
