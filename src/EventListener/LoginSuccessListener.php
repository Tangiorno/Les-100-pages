<?php

namespace App\EventListener;

use App\Entity\Utilisateur;
use App\Service\FlashMessageHelperInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessListener
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly FlashMessageHelperInterface $flashHelper
    )
    {
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $this->flashHelper->addSuccess('Connection réussie !');

        $user = $event->getUser();
        if ($user instanceof Utilisateur) {
            $user->setDateConnexion(new DateTime());
            $this->entityManager->flush();
        }
    }

}