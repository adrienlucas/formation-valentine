<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Movie;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateMovieUseCase
{

    public function __construct(
        private DenormalizerInterface $denormalizer,
        private MovieRepository $movieRepository,
        private GenreRepository $genreRepository,
        private ValidatorInterface $validator,
    )
    {
    }

    public function __invoke(string $requestBody): Movie
    {
        $json = json_decode($requestBody, true);
        $this->validateReleaseDateString($json['releaseDate']);

        /** @var Movie $movie */
        $movie = $this->denormalizer->denormalize(
            $json,
            Movie::class,
        );

        $persistedGenres = [];
        foreach($movie->getGenres() as $genre) {
            $persistedGenres[] = $this->genreRepository->retrieveOrCreate($genre);
        }
        $movie->setGenres($persistedGenres);

        //validate
        $violations = $this->validator->validate($movie);
        if ($violations->count() > 0) {
            throw new \RuntimeException((string) $violations);
        }

        $this->movieRepository->save($movie, true);

        return $movie;
    }

    private function validateReleaseDateString(?string $releaseDate)
    {
        $violations = $this->validator->validate($releaseDate, [new Date()]);
        if ($violations->count() > 0) {
            throw new \RuntimeException((string) $violations);
        }
    }

}