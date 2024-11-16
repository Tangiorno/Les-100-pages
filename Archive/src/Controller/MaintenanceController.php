<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route("/maintenance", name: 'maintenance', methods: ['GET'])]

    public function maintenance(): Response
    {
        if($this->params->get("maintenance_mode")){
            return $this->render('maintenance.html.twig');
        }
        else{
            $this->addFlash("error", "Accès non autorisé");
            return $this->redirectToRoute("liste");
        }
    }
}