<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FlashMessageHelperInterface;

class UtilisateurController extends AbstractController
{
    public function __construct(private FlashMessageHelperInterface $flashMessageHelper){}
    #[Route('/creation', name: 'creation', methods: ['GET', 'POST'])]
    public function creation(Request $request, EntityManagerInterface $manager, UtilisateurManagerInterface $utilisateurManager) : Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('accueil');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ["method" => "POST", "action"=> $this->generateUrl("creation")]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurManager->processNewUtilisateur($utilisateur, $form['plainPassword']->getData());
            $manager->persist($utilisateur);
            $manager->flush();
            $this->addFlash('success', 'Profil créé avec succès');
            return $this->redirectToRoute('accueil');
        }

        $this->flashMessageHelper->addFormErrorsAsFlash($form);

        return $this->render('utilisateur/inscription.html.twig', ["formUser"=>$form]);
    }
}
