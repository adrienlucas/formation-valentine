<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Repository\MovieRepository;

class CanListMoviesUseCase
{
    public function __construct(
        private MovieRepository $movieRepository,
    ) {
    }

    public function __invoke(): bool
    {
        return true;
    }
}