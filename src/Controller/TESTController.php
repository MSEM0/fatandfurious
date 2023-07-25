<?php

namespace App\Controller;

use App\Enum\Meals;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;



class TESTController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {
        var_dump(Meals::BRK);
        return $this->render('test/index.html.twig');}

}
