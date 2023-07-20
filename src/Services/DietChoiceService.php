<?php

namespace App\Services;

use App\Repository\MealRepository;
use Symfony\Component\HttpFoundation\Request;

const BRK = 'breakfast';
const R_BRK = 'randomBreakfast';
const DNR = 'dinner';
const R_DNR = 'randomDinner';
const SPR = 'supper';
const R_SPR = 'randomSupper';

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
                'type' => ['all', 'breakfast']
            ];
            $criteria2 = [
                'type' => ['all', 'dinner']
            ];
            $criteria3 = [
                'type' => ['all', 'supper'],
            ];

            ${R_BRK} = $this->mealRepository->getRandomMeal(BRK, $criteria1, $i, $mealSets);

            ${R_DNR} = $this->mealRepository->getRandomMeal(DNR, $criteria2, $i, $mealSets);

            ${R_SPR} = $this->mealRepository->getRandomMeal(SPR, $criteria3, $i, $mealSets);

            $totalKcal = ${R_BRK}['kcal'] + ${R_DNR}['kcal'] + ${R_SPR}['kcal'];
            $medSatisfaction = (${R_BRK}['satisfaction'] + ${R_DNR}['satisfaction'] + ${R_SPR}['satisfaction']) / 3;


            if (${R_SPR} != null && $totalKcal > $userMinKcal && $totalKcal < $userMaxKcal && $medSatisfaction > $userMedSatisfaction) {
                if ($i === 1) {
                    $date = $startDate;
                } else {
                    $date = (new \DateTime($startDate))->modify("+$i day -1 day")->format('d-m-Y');
                }
                $i++;
                $validSets[] = [
                    R_BRK => ${R_BRK},
                    R_DNR => ${R_DNR},
                    R_SPR => ${R_SPR},
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
                if ($r == 3 && $validSets[$r - 3][R_BRK]['doublePortion'] === true) {
                    $validSets[$r - 2][R_BRK] = $validSets[$r - 3][R_BRK];
                } elseif ($validSets[$r - 3][R_BRK]['doublePortion'] === true && $validSets[$r - 3][R_BRK] != $validSets[$r - 4][R_BRK]) {
                    $validSets[$r - 2][R_BRK] = $validSets[$r - 3][R_BRK];
                }
                if ($r == 3 && $validSets[$r - 3][R_DNR]['doublePortion'] === true) {
                    $validSets[$r - 2][R_DNR] = $validSets[$r - 3][R_DNR];
                } elseif ($validSets[$r - 3][R_DNR]['doublePortion'] === true && $validSets[$r - 3][R_DNR] != $validSets[$r - 4][R_DNR]) {
                    $validSets[$r - 2][R_DNR] = $validSets[$r - 3][R_DNR];
                }
                if ($r == 3 && $validSets[$r - 3][R_SPR]['doublePortion'] === true) {
                    $validSets[$r - 2][R_SPR] = $validSets[$r - 3][R_SPR];
                } elseif ($validSets[$r - 3][R_SPR]['doublePortion'] === true && $validSets[$r - 3][R_SPR] != $validSets[$r - 4][R_SPR]) {
                    $validSets[$r - 2][R_SPR] = $validSets[$r - 3][R_SPR];
                }
            }
        }
//After changes in meal sets because of double portions it calculates again the calories and satisfaction, if they won't match user's requirements, the script runs again for better outcome
        while ($a <= ($mealSets - 1) && $a >= 0) {
            $totalKcalFixed = $validSets[$a][R_BRK]['kcal'] + $validSets[$a][R_DNR]['kcal'] + $validSets[$a][R_SPR]['kcal'];
            $medSatisfactionFixed = ($validSets[$a][R_BRK]['satisfaction'] + $validSets[$a][R_DNR]['satisfaction'] + $validSets[$a][R_SPR]['satisfaction']) / 3;
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
