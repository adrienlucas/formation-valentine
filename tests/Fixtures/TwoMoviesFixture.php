<?php
declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Movie;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class TwoMoviesFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $firstMovie = new Movie();
        $firstMovie->setTitle('The Shawshank Redemption');
        $firstMovie->setDescription('...');
        $firstMovie->setReleaseDate(new \DateTime('1994-09-23'));
        $manager->persist($firstMovie);

        $secondMovie = new Movie();
        $secondMovie->setTitle('The Godfather');
        $secondMovie->setDescription('...');
        $secondMovie->setReleaseDate(new \DateTime('1972-03-24'));
        $manager->persist($secondMovie);

        $manager->flush();
    }
}