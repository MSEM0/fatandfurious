<?php

namespace App\Controller;

use App\Entity\Diet;
use App\Entity\Meal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DietChoiceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/diet/{mealSets}', name: 'app_diet_choice')]
    public function wheelOfFortune(Request $request): Response
    {
        $mealSets = $request->attributes->get('mealSets');
        $i = 1;
        $a = $mealSets - 1;

        while ($i <= $mealSets) {
            $criteria1 = [
                'type' => ['all', 'breakfast']
            ];
            $criteria2 = [
                'type' => ['all', 'dinner']
            ];
            $criteria3 = [
                'type' => ['all', 'supper']
            ];
            $totalKcal = 0;
            $satisfaction = 0;


            $queryBuilder = $this->entityManager->createQueryBuilder();

            $queryBuilder->select('breakfast')
                ->from(Meal::class, 'breakfast')
                ->where($queryBuilder->expr()->in('breakfast.type', ':types'))
                ->setParameter('types', $criteria1['type']);
            if ($i == $mealSets) {
                $queryBuilder->andWhere('breakfast.doublePortion = :doublePortion')
                    ->setParameter('doublePortion', false);
            }
            $breakfasts = $queryBuilder->getQuery()->getArrayResult();
            $randomPick1 = array_rand($breakfasts);
            $randomBreakfast = $breakfasts[$randomPick1];
            $totalKcal += $randomBreakfast['kcal'];
            $satisfaction += $randomBreakfast['satisfaction'];

            $queryBuilder->select('dinner')
                ->from(Meal::class, 'dinner')
                ->where($queryBuilder->expr()->in('dinner.type', ':types'))
                ->setParameter('types', $criteria2['type']);
            if ($i == $mealSets) {
                $queryBuilder->andWhere('dinner.doublePortion = :doublePortion')
                    ->setParameter('doublePortion', false);
            }
            $dinners = $queryBuilder->getQuery()->getArrayResult();
            $randomPick2 = array_rand($dinners);
            $randomDinner = $dinners[$randomPick2];
            $totalKcal += $randomDinner['kcal'];
            $satisfaction += $randomDinner['satisfaction'];

            $queryBuilder->select('supper')
                ->from(Meal::class, 'supper')
                ->where($queryBuilder->expr()->in('supper.type', ':types'))
                ->setParameter('types', $criteria3['type']);
            if ($i == $mealSets) {
                $queryBuilder->andWhere('supper.doublePortion = :doublePortion')
                    ->setParameter('doublePortion', false);
            }
            $suppers = $queryBuilder->getQuery()->getArrayResult();
            $randomPick3 = array_rand($suppers);
            $randomSupper = $suppers[$randomPick3];
            $totalKcal += $randomSupper['kcal'];
            $satisfaction += $randomSupper['satisfaction'];

            $medSatisfaction = $satisfaction / 3;

            if ($randomSupper != null && $totalKcal > 1000 && $totalKcal < 1700 && $medSatisfaction > 1) {
                $i++;
                $validSets[] = [
                    'randomBreakfast' => $randomBreakfast,
                    'randomDinner' => $randomDinner,
                    'randomSupper' => $randomSupper,
                    'kcal' => $totalKcal,
                    'satisfaction' => $medSatisfaction
                ];
                if ($i === 3 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 4 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 5 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 6 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 7 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 8 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 9 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 10 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }
                if ($i === 11 && $validSets[$i - 3]['randomBreakfast']['doublePortion'] === true && $validSets[$i - 3]['randomBreakfast'] != $validSets[$i - 4]['randomBreakfast']) {
                    $validSets[$i - 2]['randomBreakfast'] = $validSets[$i - 3]['randomBreakfast'];
                }

                if ($i === 3 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 4 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 5 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 6 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 7 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 8 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 9 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 10 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }
                if ($i === 11 && $validSets[$i - 3]['randomDinner']['doublePortion'] === true && $validSets[$i - 3]['randomDinner'] != $validSets[$i - 4]['randomDinner']) {
                    $validSets[$i - 2]['randomDinner'] = $validSets[$i - 3]['randomDinner'];
                }

                if ($i === 3 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 4 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 5 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 6 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 7 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 8 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 9 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 10 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
                if ($i === 11 && $validSets[$i - 3]['randomSupper']['doublePortion'] === true && $validSets[$i - 3]['randomSupper'] != $validSets[$i - 4]['randomSupper']) {
                    $validSets[$i - 2]['randomSupper'] = $validSets[$i - 3]['randomSupper'];
                }
            }
        }
        while ($a <= ($mealSets - 1) && $a >= 0) {
            $totalKcalFixed = $validSets[$a]['randomBreakfast']['kcal'] + $validSets[$a]['randomDinner']['kcal'] + $validSets[$a]['randomSupper']['kcal'];
            $medSatisfactionFixed = ($validSets[$a]['randomBreakfast']['satisfaction'] + $validSets[$a]['randomDinner']['satisfaction'] + $validSets[$a]['randomSupper']['satisfaction']) / 3;
            $validSets[$a]['kcal'] = $totalKcalFixed;
            $validSets[$a]['satisfaction'] = $medSatisfactionFixed;
            $a--;
        }

        return $this->render('diet_choice/index.html.twig', ['sets' => $validSets]);
    }
}

//foreach ($validSets as $set) {
//    $diet = new Diet();
//    $diet->setBreakfast($set['randomBreakfast']);
//    $diet->setDinner($set['randomDinner']);
//    $diet->setSupper($set['randomSupper']);
//    $diet->setKcal($set['kcal']);
//}

//0f
//1t
//2t
//3t
//4t