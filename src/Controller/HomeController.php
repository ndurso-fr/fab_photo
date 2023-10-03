<?php

namespace App\Controller;

use App\Repository\DovetailingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DovetailingRepository $dovetailingRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'dovetailings' => $dovetailingRepository->findAll(),
        ]);
    }
}
