<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\User;
use App\Form\CreationSortieType;
use App\Form\FiltresType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
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

    #[Route("/details/{id}", name: "details")]
    public function details(SortieRepository $sortieRepository, int $id)
    {

        $sortie = $sortieRepository->find($id);
        return $this->render("/sortie/details.html.twig", ['sortie' => $sortie]);
    }

    #[Route('inscription/{id}', name: 'inscription')]
    public function inscription(Sortie $sortie, UserRepository $userRepository, EntityManagerInterface $entityManager, Request $request)
    {
        if ($this->isCsrfTokenValid('inscription' . $sortie->getId(), $request->get('_token'))) {
            $sessionUser = $this->getUser()->getId();

            $user = $userRepository->find($sessionUser);

            $addParticipant = $sortie->addUser($user);

            $entityManager->persist($addParticipant);
            $entityManager->flush($addParticipant);
        }
        return $this->redirectToRoute('main_home');
    }

    #[Route('/desistement/{id}', name: 'desistement')]
    public function desistement(Sortie $sortie, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('desistement' . $sortie->getId(), $request->get('_token'))) {
            $sessionUser = $this->getUser()->getId();

            $user = $userRepository->find($sessionUser);

            $removeParticipant = $sortie->removeUser($user);

            $entityManager->persist($removeParticipant);
            $entityManager->flush($removeParticipant);
        }

        return $this->redirectToRoute('main_home');
    }

    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimerSortie(int $id,Sortie $sortie, SortieRepository $sortieRepository, EntityManagerInterface $entityManager, Request $request)
    {

        if ($this->isCsrfTokenValid('supprimer' .$id, $request->get('_token'))) {
            $sortieDelete = $sortieRepository->find($id);
            $entityManager->remove($sortieDelete);
            $entityManager->flush();
        }
        return $this->redirectToRoute('main_home');
    }
}
