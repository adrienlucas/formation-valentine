<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
