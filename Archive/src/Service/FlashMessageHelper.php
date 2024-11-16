<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FlashMessageHelper implements FlashMessageHelperInterface
{

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function addFormErrorsAsFlash(FormInterface $form): void
    {
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $errorMsg = $error->getMessage();
            $flashBag = $this->requestStack->getSession()->getFlashBag();
            $flashBag->add('error', $errorMsg);
        }
    }

    public function addSuccess(string $message): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', $message);
    }

    public function addFailure(string $message): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('error', $message);
    }
}