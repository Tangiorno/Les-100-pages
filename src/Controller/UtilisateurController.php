<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'liste')]
    public function liste(): Response
    {
        return $this->render('utilisateur/liste.html.twig', [

        ]);
    }

    #[Route('/profil/{code}', 'detailProfil', methods: ['GET'])]
    public function hello2($code, ProfilRepository $profilRepository): Response
    {
        $profil = $profilRepository->findOneBy(['codeUnique' => $code]);
        return $this->render("", ["profil" => $profil]);
    }
}
