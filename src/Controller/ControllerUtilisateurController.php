<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ControllerUtilisateurController extends AbstractController
{
    #[Route('/controller/utilisateur', name: 'app_controller_utilisateur')]
    public function index(): Response
    {
        return $this->render('controller_utilisateur/index.html.twig', [
            'controller_name' => 'ControllerUtilisateurController',
        ]);
    }
}
