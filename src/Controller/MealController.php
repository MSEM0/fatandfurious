<?php

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
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/add', name: 'app_meal')]
    public function addMeal(Request $request): Response
    {
        $meal = new Meal();
        $form = $this->createform(MealFormType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMeal = $form->getData();
            $this->entityManager->persist($newMeal);
            $this->entityManager->flush();
            Header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

        return $this->render('meal/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
