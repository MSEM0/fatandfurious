<?php

namespace App\Services;

use App\Entity\Diet;
use App\Form\ExtraMealFormType;
use App\Repository\DietRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DietDataService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly DietRepository $dietRepository,

    ) {
    }

    public function hasDuplicatedDiets(array $validSets): bool
    {
        $hasDuplicatedDiets = false;
        foreach ($validSets as $set) {
            $date = $set['date'];
            $user = $this->security->getUser();
            $duplicatedDiet = $this->dietRepository->isDiedDuplicated($user, $date);
            if (!empty($duplicatedDiet)) {
                $hasDuplicatedDiets = true;
            }
        }
        return $hasDuplicatedDiets;
    }

    public function saveDiet(array $validSets): void
    {
        foreach ($validSets as $set) {
            $breakfast = $set[R_BRK];
            $dinner = $set[R_DNR];
            $supper = $set[R_SPR];
            $date = $set['date'];
            $kcal = $set[R_BRK]['kcal'] + $set[R_DNR]['kcal'] + $set[R_SPR]['kcal'];
            $user = $this->security->getUser();
            $duplicatedDiet = $this->dietRepository->isDiedDuplicated($user, $date);

            if (!empty($duplicatedDiet)) {
                $duplicatedDiet->setBreakfast($breakfast);
                $duplicatedDiet->setDinner($dinner);
                $duplicatedDiet->setSupper($supper);
                $duplicatedDiet->setKcal($kcal);
            } else {
                $dietSet = new Diet();
                $dietSet->setBreakfast($breakfast);
                $dietSet->setDinner($dinner);
                $dietSet->setSupper($supper);
                $dietSet->setDate($date);
                $dietSet->setKcal($kcal);
                $dietSet->setUser($user);
                $this->entityManager->persist($dietSet);
            }
        }
        $this->entityManager->flush();
    }

    public function getSelectedDiets(Request $request): array
    {
        $startDate = $request->attributes->get('startDate');
        $endDate = $request->attributes->get('endDate');
        $user = $this->security->getUser();
        return $this->dietRepository->getDiets($user, $startDate, $endDate);
    }

    public function extraMealsUpdate(int $extraMeals, string $date): void
    {
        $diet = $this->dietRepository->findOneBy(['date' => $date]);

        $currentExtraMeals = $diet->getExtraMeals();
        $currentKcal = $diet->getKcal();
        $newExtraMealsValue = $currentExtraMeals + $extraMeals;
        $newKcal = $currentKcal + $extraMeals;
        $diet->setExtraMeals($newExtraMealsValue);
        $diet->setKcal($newKcal);

        $this->entityManager->flush();
    }

    public function getTotalKcal(array $selectedMeals, int $days): int
    {   $numberOfDays = 0;
        $totalKcalPerChosenPeriod = 0;
        foreach ($selectedMeals as $daily) {
            $totalKcalPerChosenPeriod += $daily['kcal'];
            $numberOfDays++;

            if ($numberOfDays >= $days) {
                break;
            }
        }
        return $totalKcalPerChosenPeriod;
    }

}