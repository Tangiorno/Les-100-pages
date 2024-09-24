<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Security\CustomRegexes;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validation;

class UtilisateurManager implements UtilisateurManagerInterface
{

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UtilisateurRepository       $utilisateurRepository
    )
    {
    }

    /**
     * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel utilisateur, après soumissions du formulaire (hachage du mot de passe...)
     */
    public function processNewUtilisateur(Utilisateur $utilisateur, ?string $plainPassword): void
    {
        $this->chiffrerMotDePasse($utilisateur, $plainPassword);
        $utilisateur->setDateConnexion(new DateTime());
        $utilisateur->setDateEdition(new DateTime());
        if ($utilisateur->getCodeUnique() == null) {
            $utilisateur->setCodeUnique(uniqid());
        }
    }

    /**
     * Chiffre le mot de passe puis l'affecte au champ correspondant dans la classe de l'utilisateur
     */
    private function chiffrerMotDePasse(Utilisateur $utilisateur, ?string $plainPassword): void
    {
        $hash = $this->userPasswordHasher->hashPassword($utilisateur, $plainPassword);
        $utilisateur->setPassword($hash);
    }

    public function processModifUtilisateur(Utilisateur $utilisateur, ?string $plainPassword, bool $passwordUpdated = false): void
    {
        if ($passwordUpdated) {
            $this->chiffrerMotDePasse($utilisateur, $plainPassword);
        } else {
            $utilisateur->setPassword($plainPassword);
        }
        $utilisateur->setDateEdition(new DateTime());
        $utilisateur->setRoles([]);
    }

    // Retourne un status code HTTP
    public function checkFieldValidity(string $key, string $value): int
    {
        $user = $this->utilisateurRepository->findOneBy([$key => $value]);

        $regexes = CustomRegexes::getRegexes();
        if (array_key_exists($key, $regexes)) {
            $regex = $regexes[$key];
            $validator = Validation::createValidator();
            $violations = $validator->validate($value, $regex);
            if (count($violations) > 0) {
                return Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        }

        return $user ? Response::HTTP_NO_CONTENT : Response::HTTP_NOT_FOUND;
    }
}
