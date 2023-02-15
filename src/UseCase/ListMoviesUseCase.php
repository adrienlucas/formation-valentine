<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Repository\MovieRepository;

class ListMoviesUseCase
{
    private const MOVIES_PER_PAGE = 5;
    public function __construct(
        private MovieRepository $movieRepository,
        private CanListMoviesUseCase $canListMoviesUseCase,
    ) {
    }

    public function __invoke(int $page): array
    {
        if (!($this->canListMoviesUseCase)()) {
            return [];
        }

        $movies = $this->movieRepository->findAll();

        $offset = ($page -1) * self::MOVIES_PER_PAGE;
        $movies = array_slice($movies, $offset, self::MOVIES_PER_PAGE);

        return $movies;
    }
}