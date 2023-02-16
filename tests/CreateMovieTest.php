<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMovieTest extends WebTestCase
{
    use FixtureRelatedTrait;
    public function testItCreatesAMovie(): void
    {
        $payload = [
            'title' => 'The Shawshank Redemption',
            'description' => '...',
            'releaseDate' => '1994-02-16',
            'genres' => [
                ['name' => 'Drama'],
                ['name' => 'Comedy']
            ]
        ];

        $client = static::createClient();
        $this->loadFixtures(new DramaFixture());

        $client->request('POST', '/movies', content: json_encode($payload));

        $this->assertResponseStatusCodeSame(201);

        $movieRepository = self::getContainer()->get(MovieRepository::class);
        $movies = $movieRepository->findAll();
        $this->assertCount(1, $movies);
        $movie = $movies[0];
        $this->assertSame('The Shawshank Redemption', $movie->getTitle());
        $this->assertSame('...', $movie->getDescription());
        $this->assertCount(2, $movie->getGenres());

        $genreRepository = self::getContainer()->get(GenreRepository::class);
        $genres = $genreRepository->findAll();
        $this->assertCount(2, $genres);
    }
}

class DramaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $drama = new Genre();
        $drama->setName('Drama');
        $manager->persist($drama);

        $manager->flush();
    }
}