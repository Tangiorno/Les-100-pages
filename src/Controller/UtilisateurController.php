<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

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
    public function afficherProfil(string $code): Response
    {
        $user = $this->utilisateurRepo->findOneBy(['codeUnique' => $code]);
        return $this->render("utilisateur/profil.html.twig", ["user" => $user]);
    }

    #[Route('/profil/{code}/json', 'detailProfiltJson', methods: ['GET'])]
    public function afficherProfilJson(string $code, SerializerInterface $serializer): Response
    {
        $user = $this->utilisateurRepo->findOneBy(['codeUnique' => $code]);
        $tab = $serializer->serialize($user, 'json');
        return $this->render("utilisateur/profilJson.html.twig", ["user" => $user, "json" => $tab]);
    }
}
