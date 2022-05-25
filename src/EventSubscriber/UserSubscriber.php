<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\ManageUserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserSubscriber implements EventSubscriberInterface
{
    private ManageUserService $manageUserService;

    public function __construct(ManageUserService $manageUserService)
    {

        $this->manageUserService = $manageUserService;
    }

    //event api platform subscriber catching request and id parameter from url

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->attributes->get('_route');
        $id = $request->attributes->get('id');

        if ($method === 'DELETE' && $route === 'api_users_delete') {
            $this->manageUserService->deleteUser($id);
        }
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::PRE_WRITE],

        ];
    }

}
