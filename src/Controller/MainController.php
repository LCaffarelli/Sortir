<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/accueil', name: 'main_home')]
    public function home(SiteRepository $siteRepository,
                         UserRepository $userRepository,
                         SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();

//        }


        return $this->render('main/home.html.twig', [
            'sorties'=>$sorties,
//            'organisateur_pseudo' => $organisateur_pseudo,
        ]);
    }
}
