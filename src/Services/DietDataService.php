<?php

namespace App\Services;

use App\Entity\Diet;
use App\Repository\DietRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Enum\Meals;

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
            $breakfast = $set[Meals::R_BRK];
            $dinner = $set[Meals::R_DNR];
            $supper = $set[Meals::R_SPR];
            $date = $set['date'];
            $kcal = $set[Meals::R_BRK]['kcal'] + $set[Meals::R_DNR]['kcal'] + $set[Meals::R_SPR]['kcal'];
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
    {
        $numberOfDays = 0;
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

    public function downloadDiet($selectedDiets)
    {
        $phpWord = new PhpWord();
        $totalDiets = count($selectedDiets);
        $loopIndex = 1;
        $section = $phpWord->addSection();

        foreach ($selectedDiets as $diet) {
            $section->addText('Date: ' . $diet['date']);
            $section->addText('Breakfast:');
            $section->addText('Name: ' . $diet[Meals::BRK]['name']);
            $section->addText('Kcal: ' . $diet[Meals::BRK]['kcal']);
            $section->addText('Satisfaction: ' . $diet[Meals::BRK]['satisfaction']);
            $section->addText('Ingredients: ' . $diet[Meals::BRK]['ingredients']);
            $section->addText('Double Portion: ' . ($diet[Meals::BRK]['doublePortion'] ? 'Yes' : 'No'));
            $section->addText('');

            $section->addText('Dinner:');
            $section->addText('Name: ' . $diet[Meals::DNR]['name']);
            $section->addText('Kcal: ' . $diet[Meals::DNR]['kcal']);
            $section->addText('Satisfaction: ' . $diet[Meals::DNR]['satisfaction']);
            $section->addText('Ingredients: ' . $diet[Meals::DNR]['ingredients']);
            $section->addText('Double Portion: ' . ($diet[Meals::DNR]['doublePortion'] ? 'Yes' : 'No'));
            $section->addText('');

            $section->addText('Supper:');
            $section->addText('Name: ' . $diet[Meals::SPR]['name']);
            $section->addText('Kcal: ' . $diet[Meals::SPR]['kcal']);
            $section->addText('Satisfaction: ' . $diet[Meals::SPR]['satisfaction']);
            $section->addText('Ingredients: ' . $diet[Meals::SPR]['ingredients']);
            $section->addText('Double Portion: ' . ($diet[Meals::SPR]['doublePortion'] ? 'Yes' : 'No'));
            $section->addText('');

            $section->addText('Total kcal: ' . $diet['kcal']);
            if ($loopIndex < $totalDiets) {
                $section->addPageBreak();
            }
            $loopIndex++;
        }

        $objWriter = IOFactory::createWriter($phpWord);

        $objWriter->save('selected_diets.docx');

        return $objWriter;
    }
}