<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\CreationSortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/creation-sortie', name: 'creation')]
    public function Sortie(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, SiteRepository $siteRepository, LieuRepository $lieuRepository): Response
    {
        $sortie = new Sortie();

        $etat = $etatRepository->find(1);

        $lieux = $lieuRepository->findAll();

        $site = $siteRepository->find($this->getUser()->getSite()->getId());

        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $sortie->setOrganisateur($this->getUser());
            $sortie->setEtat($etat);
            $sortie->setSite($site);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie added !!!');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('sortie/creationSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieux' => $lieux,
        ]);
    }


    #[Route('/tri', name: 'triSite')]
    public function triSite(SortieRepository $sortieRepository): Response
    {
        $liste = $sortieRepository->triSite();

        return $this->render('main/home.html.twig', [
//            'sortie' => $liste
        ]);
    }


    #[Route("/details/{id}", name: "details")]
    public function details(SortieRepository $sortieRepository, int $id)
    {
        $sortie = $sortieRepository->find($id);
        return $this->render("/sortie/details.html.twig", ['sortie' => $sortie]);
    }

}
