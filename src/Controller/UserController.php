<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UpdateProfileType;
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


}
