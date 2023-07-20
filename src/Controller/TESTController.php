<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TESTController extends AbstractController
{
    #[Route('/test', name: 'app_t_e_s_t')]
    public function index(Request $request): Response
    {
        $i = 4;
        $validSets = 'PRZEKAZANO';
        $startDate = '01-01-2023';
        $tomorrow = (new \DateTime($startDate))->modify("+$i day")->format('Y-m-d');

        var_dump($tomorrow);
        return $this->render('test/index.html.twig');
    }

}