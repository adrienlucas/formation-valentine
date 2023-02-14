<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    #[Route('/hello/{name}', name: 'app_hello')]
    public function index(string $name = 'world'): JsonResponse
    {
        return $this->json([
            'message' => 'Hello ' . $name . ' !',
        ]);
    }


    // BlogController

    #[Route('/blog/{page}', name: 'app_blog_articles_list', requirements: ['page' => '\d+'])]
    public function listArticles(int $page = 1)
    {
        return new JsonResponse('pages');
    }

    #[Route('/blog/featured', name: 'app_blog_articles_featured')]
    public function listFeaturedArticles()
    {
        return new JsonResponse('featured');
    }
}
