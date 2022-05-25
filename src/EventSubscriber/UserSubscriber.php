<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\ManageUserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
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

    //event api platform subscriber catching request

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->getPathInfo();

        $id = $this->explodeString($route);

        if ($method === 'DELETE') {
            $this->manageUserService->deleteUser((int)$id);
        }
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::PRE_WRITE],

        ];
    }
    //explode string /api/users/106 to array and return last element
    public function explodeString($string)
    {
        $array = explode('/', $string);
        return end($array);
    }

}
