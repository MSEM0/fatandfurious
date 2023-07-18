<?php

namespace App\Controller;

use App\Services\UserService;
use App\Twig\UserExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class UserController extends AbstractController
{


    public function __construct()
    {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

}
