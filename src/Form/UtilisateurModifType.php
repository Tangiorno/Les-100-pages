<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Security\CustomRegexes;
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
            ->add('nom', TextType::class, ["required" => false])
            ->add('prenom', TextType::class, ["required" => false])
            ->add('email', EmailType::class, ["required" => false])
            ->add('activite', TextType::class, ["required" => false])
            ->add('adressePostale', TextType::class, ["required" => false])
            ->add('codeUnique', TextType::class, ["required" => false, "constraints"=>[
                CustomRegexes::getRegexes()['codeUnique']
            ]])
            ->add('password', PasswordType::class, ["mapped" => false,"required" => false,"constraints" =>
                [new Length(["min" => 8, "max" => 50, "minMessage" => "Le mot de passe doit posséder au minimum 8 caractères", "maxMessage" => "Le mot de passe doit posséder au maximum 50 caractères"]),
                    CustomRegexes::getRegexes()['password']]])
            ->add('visible', CheckboxType::class, ["required" => false])
            ->add('numeroTelephone', TextType::class, ["required" => false, "constraints"=>[
                CustomRegexes::getRegexes()['numeroTelephone']
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
