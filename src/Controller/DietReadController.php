<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\DietReadFormType;
use App\Services\DietDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
        $totalKcalPerWeek = null;
        $selectedDiets = $this->dietDataService->getSelectedDiets($request);
        if (count($selectedDiets) >= 7) {
            $totalKcalPerWeek = $this->dietDataService->getTotalKcal($selectedDiets, 7);
        }

        if ($request->query->get('download') === 'doc') {
            $dietFile = $this->dietDataService->downloadDiet($selectedDiets);

            $tempFilePath = sys_get_temp_dir() . '/' . (new AsciiSlugger())->slug('selected_diets.docx') . '.docx';
            $dietFile->save($tempFilePath);

            $file = new \SplFileInfo($tempFilePath);

            $response = new BinaryFileResponse($file, 200, array(
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ));

            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                'selected_diets.docx'
            );

            return $response;
        }

        return $this->render('diet_read_choice/index.html.twig', [
            'selectedDiets' => $selectedDiets,
            'totalKcalPerWeek' => $totalKcalPerWeek,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
