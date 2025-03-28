<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(); 

        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setPseudo($faker->userName)
                ->setPassword($faker->password)
                ->setEmail($faker->email);
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer 10 posts
        for ($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6)) 
                ->setContent($faker->paragraph(4)) 
                ->setCreatedAt($faker->dateTimeBetween('-1 year', 'now')) 
                ->setAuthor($faker->randomElement($users));

            $manager->persist($post);

            for ($j = 0; $j < rand(1, 5); $j++) { 
                $comment = new Comment();
                $comment->setContent($faker->paragraph(2)) 
                        ->setCreatedAt($faker->dateTimeBetween($post->getCreatedAt(), 'now')) 
                        ->setAuthor($faker->randomElement($users)) 
                        ->setPost($post); 

                $manager->persist($comment);
            }
        }

        $manager->flush(); 
    }
}
