<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/accueil', name: 'main_home')]
    public function home(SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->findAll();
        dump($sortie);

        return $this->render('main/home.html.twig', [
            'sortie'=>$sortie
        ]);
    }
}
