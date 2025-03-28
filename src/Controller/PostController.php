<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(PostRepository $pr, UserRepository $ur, Security $security, TokenStorageInterface $tokenStorage): Response
    {
        $user = $ur->find(1);

        if ($user) {
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $tokenStorage->setToken($token);
        }

        $posts = $pr->findBy([], ["createdAt" => "DESC"]);
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    
    #[Route('/post/{id}', name: 'show_post')]
    public function show(Post $post, UserRepository $ur, Security $security, TokenStorageInterface $tokenStorage): Response
    {
        $user = $ur->find(1);
        
        if ($user) {
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $tokenStorage->setToken($token);
        }
        
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
