<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher)
    {
        $this->entityManager = $entityManager;

        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager)
    {
        //create User and Book fixtures using Faker and add slug with user id
        $faker = Factory::create('pl_PL');
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->hasher->hashPassword($user,'password'));
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);

            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            for ($j = 0; $j < 5; $j++) {
                $book = new Book();

                $book->setTitle($faker->sentence(3));
                $book->setDescription($faker->text(200));
                $book->setUser($user);
                $manager->persist($book);
            }



        }

        $manager->flush();

    }

}
