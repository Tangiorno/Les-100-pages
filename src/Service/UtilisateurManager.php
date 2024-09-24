<?php

namespace App\Service;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurManager implements UtilisateurManagerInterface
{

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UtilisateurRepository $utilisateurRepository
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

    public function checkFieldNotTakenNormal(string $key, string $value)
    {
        return !($this->utilisateurRepository->findOneBy([$key => $value]) === null);
    }

}