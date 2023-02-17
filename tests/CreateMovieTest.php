<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateMovieTest extends WebTestCase
{
    use FixtureRelatedTrait;

    public function testItDenyAccessWhenNotAuthenticated()
    {
        $client = self::createClient();
        $client->request('POST', '/movies', content: json_encode([]));

        $this->assertResponseStatusCodeSame(401);
    }

    public function testItDenyAccessWhenNotAdmin()
    {
        $client = self::createClient(server: [
            'PHP_AUTH_USER' => 'a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $this->loadFixtures(new UserFixture());
        //$user = $this->fixtureReferences->getReference('user');

        $client->request('POST', '/movies', content: json_encode([]));

        $this->assertResponseStatusCodeSame(403);
    }

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

        $client = static::createClient(server: [
            'PHP_AUTH_USER' => 'e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $this->loadFixtures(new DramaFixture(), new UserFixture());

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

    public function testItCannotEditAMovieThatBelongToOther()
    {
        $client = self::createClient(server: [
            'PHP_AUTH_USER' => 'b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $this->loadFixtures(new EditFixture());

        $movieId = $this->fixtureReferences->getReference('movie')->getId();

        $client->request('PUT', '/movie/' . $movieId, content: json_encode([
            'title' => 'Toto',
        ]));

        $this->assertResponseStatusCodeSame(403);
    }

    public function testItCanEditAMovie()
    {
        $client = self::createClient(server: [
            'PHP_AUTH_USER' => 'e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4',
            'PHP_AUTH_PW' => 'pass',
        ]);
        $this->loadFixtures(new EditFixture());

        $movieId = $this->fixtureReferences->getReference('movie')->getId();

        $client->request('PUT', '/movie/' . $movieId, content: json_encode([
            'title' => 'Toto',
        ]));
        // curl command to do the same:
        //
        // curl -X PUT http://127.0.0.1:8002/movies/160

//        dd($client->getResponse()->getContent());
        dump($client->getResponse()->getContent());
        dump($client->getResponse()->getStatusCode());
        $this->assertResponseIsSuccessful();
        $actualTitle = self::getContainer()->get(MovieRepository::class)->find($movieId)->getTitle();
        $this->assertSame('Toto', $actualTitle);
    }
}

class EditFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUuid('e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4');
        $admin->setPassword('$2y$13$LHNa1MmodSVNdDGnwM0j0eBDBjmvn422gvuie8Z7grU1UOzsTPIDy');
        $this->addReference('owner', $admin);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        $movie = new Movie();
        $movie->setTitle('The Shawshank Redemption');
        $movie->setDescription('...');
        $movie->setReleaseDate(new \DateTime('1994-09-23'));
        $movie->setCreatedBy($admin);
        $this->addReference('movie', $movie);
        $manager->persist($movie);

        $admin = new User();
        $admin->setUuid('b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4');
        $admin->setPassword('$2y$13$LHNa1MmodSVNdDGnwM0j0eBDBjmvn422gvuie8Z7grU1UOzsTPIDy');
        $this->addReference('other', $admin);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}

class UserFixture extends AbstractFixture {
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUuid('a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6');
        $user->setPassword('$2y$13$LHNa1MmodSVNdDGnwM0j0eBDBjmvn422gvuie8Z7grU1UOzsTPIDy');
        $this->addReference('user', $user);
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setUuid('e5f6-g7h8-i9j0-k1l2m3n4o5p6-a1b2c3d4');
        $user->setPassword('$2y$13$LHNa1MmodSVNdDGnwM0j0eBDBjmvn422gvuie8Z7grU1UOzsTPIDy');
        $user->setRoles(['ROLE_ADMIN']);
        $this->addReference('admin', $user);
        $manager->persist($user);
        $manager->flush();
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