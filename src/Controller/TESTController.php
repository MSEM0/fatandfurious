<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TESTController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {var_dump('it works');
        return $this->render('test/index.html.twig');
    }

}
