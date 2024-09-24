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

class UtilisateurCreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class, ["mapped" => false, "constraints" =>
                [new NotBlank(), new NotNull(),
                    new Length(["min" => 8, "max" => 50, "minMessage" => "Le mot de passe doit posséder au minimum 8 caractères", "maxMessage" => "Le mot de passe doit posséder au maximum 50 caractères"]),
                    CustomRegexes::getRegexes()['password']]])
            ->add('codeUnique', TextType::class, ["required" => false, "constraints" =>[
                CustomRegexes::getRegexes()['password']
            ]])
            ->add('visible', CheckboxType::class, ['required' => false, 'data' => true]) //data = true check la box par défaut
            ->add('creation', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
