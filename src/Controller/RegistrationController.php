<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CSVFormType;
use App\Form\RegistrationFormType;
use App\Repository\SiteRepository;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             UserAuthenticatorInterface $userAuthenticator,
                             AppAuthenticator $authenticator,
                             EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formCSV = $this->createForm(CSVFormType::class);
        $form->handleRequest($request);
        $user->setImage('imageDefaut.jpg');
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
            return $this->redirectToRoute('main_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'CSVForm' => $formCSV->createView(),

        ]);
    }

    #[Route('/csv2', name: 'app_registration_csv')]
    public function CSV(Request $request,
                        EntityManagerInterface $entityManager,
                        SiteRepository $siteRepository,
                        UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $formCSV = $this->createForm(CSVFormType::class);
        $formCSV->handleRequest($request);
        $csvEncoder = new CsvEncoder();

        if ($formCSV->isSubmitted() && $formCSV->isValid()) {
            // Renvoie TRUE si le nom existe et n'est pas nul && si il n'y a pas d'erreur
            if (isset($_FILES['csv_form']['name']['fichierCSV']) && $_FILES['csv_form']['error']['fichierCSV'] == 0) {
                $fichier_csv = $_FILES['csv_form']['tmp_name']['fichierCSV'];

                //Renvoie le fichier en chaine de caractère dans la variable $csvData
                $csvData = file_get_contents($fichier_csv);

                //On décode le fichier en CSV avec pour délimiteur ";"
                $data = $csvEncoder->decode($csvData, 'csv', [CsvEncoder::DELIMITER_KEY=>';']);

                //On ajoute à $User les différentes données et envoie à la BDD
                foreach ($data as $row) {
                    $site = $siteRepository->find($row['site']);
                    $user = new User();
                    $user->setSite($site);
                    $user->setPseudo($row['pseudo']);
                    $user->setPassword($userPasswordHasher->hashPassword($user, $row['password']));
                    $user->setRoles(['ROLE_USER']);
                    $user->setNom($row['nom']);
                    $user->setPrenom($row['prenom']);
                    $user->setTelephone($row['telephone']);
                    $user->setEmail($row['email']);
                    $user->setImage($row['image']);
                    $user->setActif(1);
                    $entityManager->persist($user);
                }
            $entityManager->flush();
            $this->addFlash('success', 'Inscription via fichier CSV validée !');
            return $this->redirectToRoute('main_home');
            }
       }
        return $this->render('registration/register.html.twig',[
            'registrationForm' => $form->createView(),
            'CSVForm' => $formCSV->createView(),
        ]);

    }


}
