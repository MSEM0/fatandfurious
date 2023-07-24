<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TESTController extends AbstractController
{
    #[Route('/test', name: 'app_t_e_s_t')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig');}

}
