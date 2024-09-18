<?php

namespace App\Controller;

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
}
