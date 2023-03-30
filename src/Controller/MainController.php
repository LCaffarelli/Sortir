<?php

namespace App\Controller;

use App\Entity\Sortie;
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
    public function home(SiteRepository $siteRepository,
                         Request $request,
                         SortieRepository $sortieRepository): Response
    {
        $sortie = new Sortie();

        $siteForm = $this->createForm(FiltresType::class, $sortie);

        $siteForm->handleRequest($request);

        if ($siteForm->isSubmitted() && $siteForm->isValid()) {

            $data = $siteForm->get('site')->getData();
            $sites = $siteRepository->findBy(['id' => $data->getId()]);

            foreach ($sites as $site ){
                $param = $site;
            }
            $sortie = $sortieRepository->choixSite($param);

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
