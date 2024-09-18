<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
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
        $utilisateurs = $this->utilisateurRepo->findAll();
        return $this->render('utilisateur/liste.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
