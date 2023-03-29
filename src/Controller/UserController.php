<?php

namespace App\Controller;


use App\Entity\Participant;
use App\Entity\User;
use App\Form\UpdateProfileType;
use App\Repository\ParticipantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/user/', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('profil', name: 'profil')]
    public function profil(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, Request $request)
    {
        $user = $this->getUser();
        $formUpdate = $this->createForm(UpdateProfileType::class, $user);
        $formUpdate->handleRequest($request);

        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formUpdate->get('plainPassword')->getData()
                ));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('main_home');
        }

        return $this->render('user/profil.html.twig', ['user' => $user,
            'formUpdate' => $formUpdate->createView(),
        ]);

    }
#[Route("details/{id}", name: "details")]
     public function details(int $id,UserRepository $userRepository){
        $user=$userRepository->find($id);
        return $this->render("/user/details.html.twig",['user'=>$user]);
     }

     #[Route('inscription/{id}', name:'inscription' )]
     public function inscription(){
        return $this->render('user/inscription.html.twig');
     }
     #[Route('desistement/{id}', name: 'desistement')]
     public function desistement(){
        return $this->render('user/desistement.html.twig');
     }

}
