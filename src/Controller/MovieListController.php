<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MovieListController extends AbstractController
{
    private const MOVIES_PER_PAGE = 5;

    #[Route('/movies/{page}', name:'app_movies')]
    public function list(MovieRepository $movieRepository, int $page = 1)
    {
        $movies = $movieRepository->findAll();

        $offset = ($page -1) * self::MOVIES_PER_PAGE;
        $movies = array_slice($movies, $offset, self::MOVIES_PER_PAGE);

        return $this->json($movies);
    }
}
