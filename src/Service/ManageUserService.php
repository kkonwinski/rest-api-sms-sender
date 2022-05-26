<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ManageUserService

{
    private EntityManagerInterface $entityManager;
    private SmsSenderService $smsSenderService;

    public function __construct(EntityManagerInterface $entityManager, SmsSenderService $smsSenderService)
    {
        $this->entityManager = $entityManager;
        $this->smsSenderService = $smsSenderService;
    }


    /**
     * @param int $id
     * @return void
     * @throws TransportExceptionInterface
     */
    public function deleteUser(int $id): void
    {
        //get user repository
        $userRepository = $this->entityManager->getRepository(User::class);
        //get user by id
        $user = $userRepository->find($id);
        //check if user exists
        if (!$user) {
            throw new \RuntimeException('User with id ' . $id . ' does not exist.');
        }
        //delete user
        $this->entityManager->remove($user);
//            //save changes
        $this->entityManager->flush();
        //send sms

        $this->smsSenderService->sendSms($id);


    }
}