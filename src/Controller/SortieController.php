<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulationType;
use App\Form\CreationSortieType;
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
    public function Sortie(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepository, LieuRepository $lieuRepository, UserRepository $userRepository): Response
    {
        $sortie = new Sortie();

        $user = $userRepository->find($this->getUser()->getId());

        $lieux = $lieuRepository->findAll();

        $site = $siteRepository->find($this->getUser()->getSite()->getId());

        $sortieForm = $this->createForm(CreationSortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $data = $sortieForm->get('inscrire')->getData();
            if ($data == "on") {
                $sortie->addUser($user);
            }
            if ($sortieForm->get('publier')->isClicked()) {
                $sortie->setEtat($entityManager->find(Etat::class, 2));
            }
            if ($sortieForm->get('cree')->isClicked()) {
                $sortie->setEtat($entityManager->find(Etat::class, 1));
            }
            $sortie->setOrganisateur($user);
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
        $finInscription = false;
        $date = new \DateTime("now");
        foreach ($sortie->getUsers() as $user) {
            if ($user->getId() == $this->getUser()->getId()) {
                $finInscription = true;
            }
        }

        if (count($sortie->getUsers()) == $sortie->getNbInscriptionsMax() || $sortie->getDateLimiteInscription() < $date) {
            $finInscription = true;
        }else if ($sortie->getEtat()->getId() != 2){
            $finInscription = true;
        }
        return $this->render("/sortie/details.html.twig", [
            'sortie' => $sortie,
            'finInscription' => $finInscription,
        ]);
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

    #[Route('/annuler/{id}', name: 'annuler')]
    public function annulerSortie(int $id, Sortie $sortie, SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $sortie = $sortieRepository->find($id);
        $sortieAnnuleeForm = $this->createForm(AnnulationType::class, $sortie);
        $sortieAnnuleeForm->handleRequest($request);

        if($sortieAnnuleeForm->isSubmitted() && $sortieAnnuleeForm->isValid()){
            if ($this->isCsrfTokenValid('annuler' . $id, $request->get('_token'))) {

                $sortie->setEtat($etatRepository->find(6));

                $entityManager->persist($sortie);
                $entityManager->flush();
                return $this->redirectToRoute('main_home');
            }
        }

        return $this->render('/sortie/annuler.html.twig', ['sortieAnnuleeForm' => $sortieAnnuleeForm->createView(), 'sortie'=>$sortie]);
    }
}
