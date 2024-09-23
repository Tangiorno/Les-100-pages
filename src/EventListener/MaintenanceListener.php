<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class MaintenanceListener
{
    private bool $maintenanceMode;
    private RouterInterface $router;

    public function __construct(bool $maintenanceMode, RouterInterface $router)
    {
        $this->maintenanceMode = $maintenanceMode;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->maintenanceMode) {
            $request = $event->getRequest();
            $currentRoute = $request->attributes->get('_route');
            if ($currentRoute !== 'maintenance') {
                $url = $this->router->generate('maintenance');
                $event->setResponse(new RedirectResponse($url));
            }
        }
    }
}
