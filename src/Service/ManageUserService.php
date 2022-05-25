<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ManageUserService

{
    private EntityManagerInterface $entityManager;
    private SmsSenderService $smsSenderService;

    public function __construct(EntityManagerInterface $entityManager, SmsSenderService $smsSenderService)
    {
        $this->entityManager = $entityManager;
        $this->smsSenderService = $smsSenderService;
    }


    public function deleteUser(int $id)
    {
        //get user repository
        $userRepository = $this->entityManager->getRepository(User::class);
        //get user by id
        $user = $userRepository->find($id);
        //check if user exists
        if ($user) {
            //delete user
            $this->entityManager->remove($user);
            //save changes
            $this->entityManager->flush();
            //send sms

            $this->smsSenderService->sendSms($id);
        }



    }
}