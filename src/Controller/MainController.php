<?php

namespace App\Controller;

use App\Class\FiltresSorties;
use App\Entity\Sortie;
use App\Form\FiltresSortieType;
use App\Form\FiltresType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/accueil', name: 'main_home')]
    public function home(UserRepository $userRepository,
                         Request $request,
                         SortieRepository $sortieRepository): Response
    {
        $filtres = new FiltresSorties();

        $userCo = $userRepository->find($this->getUser()->getId());

        $siteForm = $this->createForm(FiltresSortieType::class, $filtres);

        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {

            $date = date('Y-m-d');

            $sortie = $sortieRepository->filtre($filtres, $userCo, $date);

            return $this->render('main/home.html.twig', [
                'sites' => $siteForm->createView(),
                'sorties' => $sortie,
            ]);
        } else {
            $sorties = $sortieRepository->findAll();
            dump($siteForm->getData());
            return $this->render('main/home.html.twig', [
                'sites' => $siteForm,
                'sorties' => $sorties,
            ]);
        }
    }
}
