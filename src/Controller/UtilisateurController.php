<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{
    public function __construct(
        private readonly UtilisateurRepository $utilisateurRepo
    )
    {
    }

    #[Route('/', name: 'liste')]
    public function liste(): Response
    {
        $utilisateurs = $this->utilisateurRepo->findBy(['visible' => true]);
        return $this->render('utilisateur/liste.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/profil/{code}', 'detailProfil', methods: ['GET'])]
    public function hello2($code, ProfilRepository $profilRepository): Response
    {
        $profil = $profilRepository->findOneBy(['codeUnique' => $code]);
        return $this->render("", ["profil" => $profil]);
    }
}
