<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\UseCase\CreateMovieUseCase;
use App\UseCase\ListMoviesUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieListController extends AbstractController
{
    private const MOVIES_PER_PAGE = 5;

    #[Route('/movies/{page}', name:'app_movies_list', methods: ['GET'])]
    public function list(ListMoviesUseCase $usecase, int $page = 1)
    {
        $movies = $usecase($page);
        return $this->json($movies);
    }

    #[Route('/movies', name:'app_movies_create', methods: ['POST'])]
    public function create(CreateMovieUseCase $usecase, Request $request)
    {
        $movie = $usecase($request->getContent());
        return $this->json($movie, Response::HTTP_CREATED);
    }

    #[Route('/movie/{id}', name:'app_movie')]
    public function show(Movie $movie)
    {
        return $this->json($movie);
    }
}
