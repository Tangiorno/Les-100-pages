<?php

namespace App\EventListener;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use App\Entity\Utilisateur;

class LoginSuccessListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof Utilisateur) {
            $user->setDateConnexion(new DateTime());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

}