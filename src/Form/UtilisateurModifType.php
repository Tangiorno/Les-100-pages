<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class UtilisateurModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, ["required" => false])
            ->add('prenom', TextType::class, ["required" => false])
            ->add('nom', TextType::class, ["required" => false])
            ->add('email', EmailType::class, ["required" => false])
            ->add('codeUnique', TextType::class, ["required" => false, "constraints"=>[
                new Regex('/^[a-zA-Z0-9]+$/', 'Le code unique doit être composé uniquement de caractères alphanumériques')
            ]])
            ->add('password', PasswordType::class, ["mapped" => false,"required" => false,"constraints" =>
                [new NotBlank(), new NotNull(),
                    new Length(["min" => 8, "max" => 50, "minMessage" => "Le mot de passe doit posséder au minimum 8 caractères", "maxMessage" => "Le mot de passe doit posséder au maximum 50 caractères"]),
                    new Regex("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#", "Le mot de passe doit contenir au minimum une majuscule, une minuscule et un chiffre")]])
            ->add('visible', CheckboxType::class, ["required" => false])
            ->add('numeroTelephone', TextType::class, ["required" => false, "constraints"=>[
                new Regex('/^\+?(\d{1,4})?[-.\s]?(\(?\d{1,3}\)?[-.\s]?)?[\d\s.-]{7,15}$/', 'Entrez un numéro de téléphone valide.')
            ]])
            ->add('pays', TextType::class, ["required" => false])
            ->add('edition', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
