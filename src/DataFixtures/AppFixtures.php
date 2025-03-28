<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); 

        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setPseudo($faker->userName)
                // ->setPassword($faker->password) 
                ->setEmail($faker->safeEmail);

            $plainPassword = 'password123'; 

            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user; 
        }

        for ($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setTitle($faker->realText(50))  
                ->setContent($faker->realText(200))  
                ->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'))  
                ->setAuthor($faker->randomElement($users));  

            $manager->persist($post);

            for ($j = 0; $j < rand(1, 5); $j++) { 
                $comment = new Comment();
                $comment->setContent($faker->realText(100)) 
                        ->setCreatedAt($faker->dateTimeBetween($post->getCreatedAt(), 'now')) 
                        ->setAuthor($faker->randomElement($users))  
                        ->setPost($post);  

                $manager->persist($comment);
            }
        }

        $manager->flush(); 
    }
}
