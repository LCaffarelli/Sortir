<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\CreationSortieType;
use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/creation-sortie', name: 'creation')]
    public function Sortie(Request $request, EntityManagerInterface $entityManager, EtatRepository $etatRepository, SiteRepository $siteRepository): Response
    {
        $sortie = new Sortie();

        $etat = $etatRepository->find(1);

        $site = $siteRepository->find($this->getUser()->getSite()->getId());

        $organisateur = new Participant();

        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $organisateur->setNom($this->getUser()->getNom());
            $organisateur->setPrenom($this->getUser()->getPrenom());
            $organisateur->setTelephone($this->getUser()->getTelephone());
            $organisateur->setMail($this->getUser()->getEmail());
            $organisateur->setAdministrateur(false);
            $organisateur->setActif(true);
            $organisateur->setSite($site);
            $sortie->setOrganisateur($organisateur);
            $sortie->setEtat($etat);
            $sortie->setSite($site);
            $entityManager->persist($organisateur);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie added !!!');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('sortie/creationSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

}
