<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Diet;
use App\Repository\DietRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\WriterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\Meals;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\String\Slugger\AsciiSlugger;

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

    public function deleteExtraMeals(string $date): void
    {
        $diet = $this->dietRepository->findOneBy(['date' => $date]);
        $diet->setExtraMeals(null);
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

    public function downloadDiet(array $selectedDiets)
    {
        $phpWord = new PhpWord();
        $totalDiets = count($selectedDiets);
        $loopIndex = 1;
        $mealTypes = [
            Meals::BRK,
            Meals::DNR,
            Meals::SPR,
        ];
        $section = $phpWord->addSection();
        $textStyleBold = new Font();
        $textStyleLg = new Font();
        $textStyleLg->setSize(14);
        $textStyleBold->setBold();

        foreach ($selectedDiets as $diet) {
            $section->addText('Date: ' . $diet['date'], $textStyleLg);
            $section->addText('');

            foreach ($mealTypes as $mealType) {
                $section->addText(ucfirst($mealType) . ':', $textStyleBold);
                $section->addText('Name: ' . $diet[$mealType]['name']);
                $section->addText('Kcal: ' . $diet[$mealType]['kcal']);
                $section->addText('Satisfaction: ' . $diet[$mealType]['satisfaction']);
                $section->addText('Ingredients: ' . $diet[$mealType]['ingredients']);
                $section->addText('Double Portion: ' . ($diet[$mealType]['doublePortion'] ? 'Yes' : 'No'));
                $section->addText('');
            }

            $section->addText('Total kcal: ' . $diet['kcal']);

            if ($loopIndex < $totalDiets) {
                $section->addPageBreak();
            }
            $loopIndex++;
        }

        $objWriter = IOFactory::createWriter($phpWord);

        $objWriter->save('selected_diets.docx');

        $tempFilePath = sys_get_temp_dir() . '/' . (new AsciiSlugger())->slug('selected_diets.docx') . '.docx';
        $objWriter->save($tempFilePath);
        $file = new \SplFileInfo($tempFilePath);

        $response = new BinaryFileResponse($file, 200, array(
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ));

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'selected_diets.docx'
        );
        $response->deleteFileAfterSend();

        return $response;
    }
}