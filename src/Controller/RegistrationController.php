<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_USER']);
            $user->setActif(true);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Inscription validée !');
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/csvlol', name: 'app_registration_csv')]
    public function CSV(EntityManagerInterface $entityManager){
        $csvEncoder = new CsvEncoder();
        $csvData = file_get_contents('C:\wamp64\www\Sortir\public\output.csv'); // Le chemin vers le fichier CSV soumis
        $data = $csvEncoder->decode($csvData, 'csv');


        foreach ($data as $row) {
            $entity = new User();
            $entity->setSite($row['site']);
            $entity->setRoles(['ROLE_USER']);
            $entity->setPseudo($row['pseudo']);
            $entity->setPassword($row['nom']);
            $entity->setPassword($row['prenom']);
            $entity->setPassword($row['telephone']);
            $entity->setPassword($row['email']);
            $entity->setPassword($row['image']);
            $entity->setActif(1);


            $entityManager->persist($entity);//lol
        }

        $entityManager->flush();
        return $this->redirectToRoute('main_home');

    }


}
