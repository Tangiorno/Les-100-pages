<?php

namespace App\EventListener;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginFailureListener
{

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $this->requestStack->getSession()->getFlashBag()->add('error', 'Identifiants invalides. Veuillez réessayer.');
    }

}