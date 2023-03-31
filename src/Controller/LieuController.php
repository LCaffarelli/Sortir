<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route('/lieu/{id}', name: 'app_lieu')]
    public function AffichageLieu(int $id, LieuRepository $lieuRepository): Response
    {
        $affichageLieu = $lieuRepository->find($id);

        if(!$affichageLieu){
            throw $this->createNotFoundException("Le lieu n'existe pas");
        }
        return $this->json([
            'ville' => $affichageLieu->getVille()->getNom(),
            'Rue' => $affichageLieu->getRue(),
            'code' => $affichageLieu->getVille()->getCodePostal(),
            'latitude' => $affichageLieu->getLatitude(),
            'longitude' => $affichageLieu->getLongitude(),
        ]);
    }
}
