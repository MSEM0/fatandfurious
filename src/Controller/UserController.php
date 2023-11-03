<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/settings', name: 'app_settings', methods: ['GET', 'POST'])]
    public function userSettings(Request $request) : Response
    {
        $usersChoices = $this->userService->getUsersChoices();

        if ($request->isMethod('POST')) {
            $choice = $request->get('choice');
            $newValue = intval($request->get('newValue'));

            $this->userService->setUsersChoice($choice, $newValue);

            return $this->redirectToRoute('app_settings');
        }

        return $this->render('user_settings/index.html.twig', ['usersChoices' => $usersChoices]);
    }
}
