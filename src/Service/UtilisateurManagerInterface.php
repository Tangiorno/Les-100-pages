<?php

namespace App\Service;

use App\Entity\Utilisateur;

interface UtilisateurManagerInterface
{
    /**
     * Réalise toutes les opérations nécessaires avant l'enregistrement en base d'un nouvel utilisateur, après soumissions du formulaire (hachage du mot de passe...)
     */
    public function processNewUtilisateur(Utilisateur $utilisateur, ?string $plainPassword): void;
}