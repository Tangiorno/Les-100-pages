<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceController extends AbstractController
{

    #[Route("/maintenance", name: 'maintenance', methods: ['GET'])]

    public function maintenance(): Response
    {
        return $this->render('maintenance.html.twig');
    }
}