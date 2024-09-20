<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\UtilisateurManager;
use App\Service\UtilisateurManagerInterface;
use App\U;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FlashMessageHelperInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

class UtilisateurController extends AbstractController
{
    public function __construct(private readonly FlashMessageHelperInterface $flashMessageHelper,
                                private readonly UtilisateurRepository       $utilisateurRepo)
    {
    }


    #[Route('/', name: 'liste', methods: ['GET'])]
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
        $currentConnectedUser = $this->getUser();
        $user = $this->utilisateurRepo->findOneBy(['codeUnique' => $code]);

        $isSelfProfile = $currentConnectedUser && $currentConnectedUser->getUserIdentifier() == $user->getCodeUnique();

        return $this->render("utilisateur/profil.html.twig", ["user" => $user, "isSelfProfile" => $isSelfProfile]);
    }

    #[Route('/creation', name: 'creation', methods: ['GET', 'POST'])]
    public function creation(Request $request, EntityManagerInterface $manager, UtilisateurManagerInterface $utilisateurManager, Security $security): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur, ["method" => "POST", "action" => $this->generateUrl("creation")]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurManager->processNewUtilisateur($utilisateur, $form['plainPassword']->getData(), $form['visible']->getData());
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash('success', 'Profil créé avec succès');

            $security->login($utilisateur);

            return $this->redirectToRoute('liste');
            /*return $userAuthenticator->authenticateUser(
                $utilisateur,
                $authenticator,
                $request
            );*/
        }

        $this->flashMessageHelper->addFormErrorsAsFlash($form);

        return $this->render('utilisateur/creation.html.twig', ["formUser" => $form]);
    }

    #[Route('/edition', name: 'edition', methods: ['GET', 'POST'])]
    public function modifier(Request $request, EntityManagerInterface $manager, UtilisateurManagerInterface $utilisateurManager): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $user = $this->getUser();

        U::pd("user à modifier :", $user);

        // !!!!!!!!!!! Ce code commenté c'est pour une template de modification, mais là c'est juste celui de création.
        // !!!!!!!!!!! Si la route c'est pas un truc genre /edition/{code} c'est parce qu'on peut seulement modifier
        // !!!!!!!!!!! son propre profil donc autant juste modifier getUser().

        /*        $form = $this->createForm(UtilisateurType::class, $utilisateur, ["method" => "POST", "action" => $this->generateUrl("creation")]);

                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $utilisateurManager->processNewUtilisateur($utilisateur, $form['plainPassword']->getData(), $form['visible']->getData());
                    $manager->persist($utilisateur);
                    $manager->flush();
                    $this->addFlash('success', 'Profil créé avec succès');
                    return $this->redirectToRoute('liste');
                }

                $this->flashMessageHelper->addFormErrorsAsFlash($form);


                return $this->render('utilisateur/creation.html.twig', ["formUser" => $form]);
        */
    }

    #[Route('/suppression', name: 'suppression', methods: ['POST'])]
    public function supprimer(Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $user = $this->getUser();

        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('detailProfil', ['code' => $user->getUserIdentifier()]);
        }

        $manager->remove($user);
        $manager->flush();

        $security->logout();

        return $this->redirectToRoute('liste');
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('utilisateur/connexion.html.twig', ["lastUsername" => $lastUsername]);
    }
}
