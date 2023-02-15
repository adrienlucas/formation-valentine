<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private ValidatorInterface $validator,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        $action = new Genre();
        $action->setName('Action');
        $manager->persist($action);

        $comedy = new Genre();
        $comedy->setName('Comedy');
        $manager->persist($comedy);

        $drama = new Genre();
        $drama->setName('Drama');
        $manager->persist($drama);

        $movie = new Movie();
        $movie->setTitle('The Shawshank Redemption');
        $movie->setDescription('Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.');
        $movie->addGenre($action);
        $movie->setReleaseDate(new DateTime('1994-10-14'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Godfather');
        $movie->setDescription('The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.');
        $movie->addGenre($action);
        $movie->setReleaseDate(new DateTime('1972-03-24'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setDescription('When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.');
        $movie->addGenre($action);
        $movie->setReleaseDate(new DateTime('2008-07-18'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Hangover');
        $movie->setDescription('Three buddies wake up from a bachelor party in Las Vegas, with no memory of the previous night and the bachelor missing. They make their way around the city in order to find their friend before his wedding.');
        $movie->addGenre($comedy);
        $movie->setReleaseDate(new DateTime('2009-06-05'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Hangover Part II');
        $movie->setDescription('Two years after the bachelor party in Las Vegas, Phil, Stu, Alan, and Doug jet to Thailand for Stus wedding. Stus plan for a subdued pre-wedding brunch, however, goes seriously awry.');
        $movie->addGenre($comedy);
        $movie->setReleaseDate(new DateTime('2011-05-26'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Hangover Part III');
        $movie->setDescription('When one of their own is kidnapped by an angry gangster, the Wolf Pack must track down Mr. Chow, who has escaped from prison and is on the run.');
        $movie->addGenre($comedy);
        $movie->setReleaseDate(new DateTime('2013-05-23'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Godfather: Part II');
        $movie->setDescription('The early life and career of Vito Corleone in 1920s New York is portrayed while his son, Michael, expands and tightens his grip on his crime syndicate stretching from Lake Tahoe, Nevada to pre-revolution 1958 Cuba.');
        $movie->addGenre($drama);
        $movie->setReleaseDate(new DateTime('1974-12-20'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('Pulp Fiction');
        $movie->setDescription('The lives of two mob hitmen, a boxer, a gangsters wife, and a pair of diner bandits intertwine in four tales of violence and redemption.');
        $movie->addGenre($drama);
        $movie->setReleaseDate(new DateTime('1994-10-14'));
        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('The Dark Knight Rises');
        $movie->setDescription('Eight years after the Joker\'s reign of anarchy, the Dark Knight, with the help of the enigmatic Catwoman, is forced from his imposed exile to save Gotham City, now on the edge of total annihilation, from the brutal guerrilla terrorist Bane.');
        $movie->addGenre($drama);
        $movie->setReleaseDate(new DateTime('2012-07-20'));
        $manager->persist($movie);

        $manager->flush();
    }
}


