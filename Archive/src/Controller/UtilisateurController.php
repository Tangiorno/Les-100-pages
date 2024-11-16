<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurCreaType;
use App\Form\UtilisateurModifType;
use App\Repository\UtilisateurRepository;
use App\Security\CustomRegexes;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;

class UtilisateurController extends AbstractController
{
    public function __construct(private readonly FlashMessageHelperInterface $flashMessageHelper,
                                private readonly UtilisateurRepository $utilisateurRepo,
                                private readonly UtilisateurManagerInterface $utilisateurManager,)
    {
    }


    #[Route('/', name: 'liste', methods: ['GET'])]
    public function liste(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $utilisateurs = $this->utilisateurRepo->findAll();
        } else {
            $utilisateurs = $this->utilisateurRepo->findBy(['visible' => true]);
        }

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
        $form = $this->createForm(UtilisateurCreaType::class, $utilisateur, ["method" => "POST", "action" => $this->generateUrl("creation")]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurManager->processNewUtilisateur($utilisateur, $form['plainPassword']->getData());
            $manager->persist($utilisateur);
            $manager->flush();

            $this->addFlash('success', 'Profil créé avec succès');

            $security->login($utilisateur);

            return $this->redirectToRoute('liste');
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

        $user = $manager->getRepository(Utilisateur::class)->findOneBy(["codeUnique" => $user->getUserIdentifier()]);
        $hashedPassword = $user->getPassword();
        $form = $this->createForm(UtilisateurModifType::class, $user, ["method" => "POST", "action" => $this->generateUrl("edition")]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['codeUnique']->getData() == null) {
                $user->setCodeUnique(uniqid());
            }
            if ($form['password']->getData() == null) {
                $utilisateurManager->processModifUtilisateur($user, $hashedPassword);
            } else {
                $utilisateurManager->processModifUtilisateur($user, $form['password']->getData(), true);
            }
            $manager->flush();
            $this->addFlash('success', 'Profil modifié avec succès');
            return $this->redirectToRoute('detailProfil', ['code' => $user->getCodeUnique()]);
        }

        $this->flashMessageHelper->addFormErrorsAsFlash($form);

        return $this->render('utilisateur/edition.html.twig', ["formUser" => $form, 'user' => $user]);

    }

    #[Route('/suppression/{code}', name: 'suppression', methods: ['POST'])]
    public function supprimer(string $code, Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $currentUser = $this->getUser();

        if (!$this->isGranted('ROLE_USER') || !($currentUser->getUserIdentifier() == $code || $this->isGranted('ROLE_ADMIN'))) {
            return $this->redirectToRoute('liste');
        }

        $userToDelete = $manager->getRepository(Utilisateur::class)->findOneBy(["codeUnique" => $code]);

        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('detailProfil', ['code' => $code]);
        }

        $logoutUser = $currentUser->getUserIdentifier() == $userToDelete->getUserIdentifier();

        $manager->remove($userToDelete);
        $manager->flush();

        if ($logoutUser) $security->logout(false);

        $this->addFlash('success', 'Profil supprimé avec succès');

        return $this->redirectToRoute('liste');
    }

    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils, EntityManagerInterface $manager): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('liste');
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('utilisateur/connexion.html.twig', ["lastUsername" => $lastUsername]);
    }

    #[Route('/profil/{code}/json', 'detailProfiltJson', methods: ['GET'])]
    public function afficherProfilJson(string $code, SerializerInterface $serializer): Response
    {
        $user = $this->utilisateurRepo->findOneBy(['codeUnique' => $code]);
        $tab = $serializer->serialize($user, 'json');
        $response = new Response();
        $response->setContent($tab);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    #[Route('/check-field-not-taken/{key}/{value}', name: 'check_field_not_taken', options: ["expose" => true], methods: ['HEAD'])]
    public function checkFieldNotTaken(string $key, string $value): JsonResponse
    {
        return new JsonResponse("", $this->utilisateurManager->checkFieldValidity($key, $value));
    }
}
