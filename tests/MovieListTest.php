<?php
declare(strict_types=1);

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use App\Entity\Movie;
use App\Tests\Fixtures\NineMoviesFixture;
use App\Tests\Fixtures\TwoMoviesFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieListTest extends WebTestCase
{
    use FixtureRelatedTrait;

    public function testItListMoviesWithPagination()
    {
        $client = self::createClient();
        $this->loadFixtures(new NineMoviesFixture());
        $movie = $this->fixtureReferences->getReference('godfather');

        $client->request('GET', '/movie/'.$movie->getId());

        $this->assertResponseIsSuccessful();
        $movies = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(5, $movies); // The number of movies per page is 5
        $this->assertArrayHasKey('title', $movies[0]);
        $this->assertArrayHasKey('description', $movies[0]);
        $this->assertSame('The Shawshank Redemption', $movies[0]['title']);
    }

    public function testItListMoviesWithoutPagination()
    {
        $client = self::createClient();
        $this->loadFixtures(new TwoMoviesFixture());
        $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();
        $movies = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(2, $movies);
    }

    public function testItListNoMovies()
    {
        $client = self::createClient();
        $this->loadFixtures();
        $client->request('GET', '/movies');

        $this->assertResponseIsSuccessful();
        $movies = json_decode($client->getResponse()->getContent(), true);

        $this->assertCount(0, $movies);
    }

}
