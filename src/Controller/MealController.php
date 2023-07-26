<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Meal;
use App\Form\MealFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MealController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/add', name: 'app_meal')]
    public function addMeal(Request $request): Response
    {
        $newMeal = new Meal();
        $form = $this->createform(MealFormType::class, $newMeal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMeal = $form->getData();
            $this->entityManager->persist($newMeal);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_meal');
        }

        return $this->render('meal/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
