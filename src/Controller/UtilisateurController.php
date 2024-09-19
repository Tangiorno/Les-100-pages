<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FlashMessageHelperInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UtilisateurController extends AbstractController
{
    public function __construct(private FlashMessageHelperInterface $flashMessageHelper,
                                private readonly UtilisateurRepository $utilisateurRepo){}


    #[Route('/', name: 'liste', methods: ['GET'])]
    public function liste(): Response
    {
        $utilisateurs = $this->utilisateurRepo->findBy(['visible' => true]);
        return $this->render('utilisateur/liste.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/profil/{code}', name: 'profil', methods: ['GET'])]
    public function profil(string $code): Response
    {
        print_r("hi $code !");
        die();
    }

    #[Route('/creation', name: 'creation', methods: ['GET', 'POST'])]
    public function creation(Request $request, EntityManagerInterface $manager, UtilisateurManagerInterface $utilisateurManager) : Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ["method" => "POST", "action"=> $this->generateUrl("creation")]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurManager->processNewUtilisateur($utilisateur, $form['plainPassword']->getData());
            $manager->persist($utilisateur);
            $manager->flush();
            $this->addFlash('success', 'Profil créé avec succès');
            return $this->redirectToRoute('liste');
        }

        $this->flashMessageHelper->addFormErrorsAsFlash($form);


        return $this->render('utilisateur/creation.html.twig', ["formUser"=>$form]);
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils) : Response
    {
        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('utilisateur/connexion.html.twig', ["lastUsername" => $lastUsername]);
    }
}
