<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieEditUseCase
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
    )
    {
    }


    public function __invoke(Movie $movie, string $payload): Movie
    {
        $this->serializer->deserialize(
            $payload, Movie::class, 'json', [
            AbstractNormalizer::GROUPS => ['web'],
            AbstractNormalizer::OBJECT_TO_POPULATE => $movie,
        ]);

        $violationList = $this->validator->validate($movie);
        if (count($violationList) > 0) {
            throw new \RuntimeException((string) $violationList);
        }

        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return $movie;
    }
}