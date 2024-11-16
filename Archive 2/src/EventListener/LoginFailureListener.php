<?php

namespace App\EventListener;

use App\Service\FlashMessageHelperInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureListener implements AuthenticationFailureHandlerInterface
{
    public function __construct(private readonly RouterInterface $router, private readonly FlashMessageHelperInterface $flashHelper)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        $this->flashHelper->addFailure('La connexion a échoué. Veuillez vérifier vos identifiants.');

        return new RedirectResponse($this->router->generate('connexion'));
    }
}