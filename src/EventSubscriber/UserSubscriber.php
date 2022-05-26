<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Service\ManageUserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private ManageUserService $manageUserService;

    public function __construct(ManageUserService $manageUserService)
    {
        $this->manageUserService = $manageUserService;
    }

    //event api platform subscriber catching request

    /**
     * @throws TransportExceptionInterface
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->getPathInfo();

        $id = $this->explodeString($route);

        if ($method === 'DELETE') {
            $sendSmsApiResult = $this->manageUserService->deleteUser((int)$id);
            return new JsonResponse($sendSmsApiResult);
        }
    }


    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::PRE_WRITE],

        ];
    }


    /**
     * explode string /api/users/106 to array and return last element
     * @param string $string
     * @return false|string
     */
    public function explodeString(string $string): bool|string
    {
        $array = explode('/', $string);
        return end($array);
    }

}
