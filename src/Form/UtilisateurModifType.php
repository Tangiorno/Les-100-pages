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

class UtilisateurModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class, ["required" => false])
            ->add('prenom', TextType::class, ["required" => false])
            ->add('nom', TextType::class, ["required" => false])
            ->add('email', EmailType::class, ["required" => false])
            ->add('codeUnique', TextType::class, ["required" => false])
            ->add('password', PasswordType::class, ["required" => false])
            ->add('visible', CheckboxType::class)
            ->add('numeroTelephone', TextType::class, ["required" => false])
            ->add('pays', TextType::class, ["required" => false])
            ->add('edition', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
