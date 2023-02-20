<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Fixtures\FixtureFactory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class GetMovieCollectionTest extends ApiTestCase
{
    use FixtureRelatedTrait;

    /**
     * @dataProvider provideFilters
     */
    public function testItShowsTheFilteredListOfMovies(string $filter, int $expectedNumberOfMovies): void
    {
        $client = static::createClient();
        $this->loadFixtures(new MovieCollectionFixture());

        $response = $client->request('GET', '/api/movies?'.$filter);

        $this->assertResponseIsSuccessful();
        $movies = $response->toArray()['hydra:member'];
        $this->assertCount($expectedNumberOfMovies, $movies);
    }

    public function provideFilters(): array
    {
        return [
            'filtering by title' => ['title=the', 2],
            'filtering by releaseDate' => ['releaseDate[after]=2020-01-01   ', 1],
        ];
    }
}

class MovieCollectionFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $owner = FixtureFactory::makeUser();
        $manager->persist($owner);

        $movie = FixtureFactory::makeMovie(
            title: 'The Matrix 7',
            releaseDate: new \DateTime('2021-01-01'),
            createdBy: $owner,
        );
        $manager->persist($movie);

        $movie = FixtureFactory::makeMovie(
            title: 'The Shawshank Redemption',
            releaseDate: new \DateTime('1994-09-23'),
            createdBy: $owner,
        );
        $manager->persist($movie);

        $movie = FixtureFactory::makeMovie(
            title: 'Avatar',
            releaseDate: new \DateTime('2009-12-18'),
            createdBy: $owner,
        );
        $manager->persist($movie);

        $manager->flush();
    }
}