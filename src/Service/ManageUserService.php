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


    public function deleteUser(mixed $id)
    {
        //get user repository
        $userRepository = $this->entityManager->getRepository(User::class);
        //get user by id
        $user = $userRepository->find($id);
        //check if user exists
        if ($user) {
            //get user books
            $books = $user->getBooks();
            //check if user has books
            if ($books) {
                //remove user books
                $this->entityManager->remove($books);
            }
            //remove user
            $this->entityManager->remove($user);
            //flush changes
            $this->entityManager->flush();
            $this->smsSenderService->sendSms($user->getId());
        }
    }
}