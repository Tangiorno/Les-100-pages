<?php

namespace App\Security;

use Symfony\Component\Validator\Constraints\Regex;

class CustomRegexes
{
    public static function regexMdp() : Regex
    {
        return new Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", "Le mot de passe doit contenir au minimum une majuscule, une minuscule et un chiffre");
    }

    public static function regexPhone() : Regex
    {
        return new Regex('/^\+?(\d{1,4})?[-.\s]?(\(?\d{1,3}\)?[-.\s]?)?[\d\s.-]{7,15}$/', 'Entrez un numéro de téléphone valide.');
    }

    public static function regexCodeUnique() : Regex
    {
        return new Regex('/^[a-zA-Z0-9]+$/', 'Le code unique doit être composé uniquement de caractères alphanumériques');
    }
}