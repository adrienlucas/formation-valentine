<?php
declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\Movie;
use App\Entity\User;

class FixtureFactory
{
    public static function makeMovie(
        string $title = '...',
        string $description = '...',
        array $genres = [],
        \DateTime $releaseDate = null,
        ?User $createdBy = null,
    ): Movie
    {
        if($releaseDate === null) {
            $releaseDate = new \DateTime('now');
        }
        if($createdBy === null) {
            $createdBy = self::makeUser();
        }

        $movie = new Movie();
        $movie->setTitle($title);
        $movie->setGenres($genres);
        $movie->setDescription($description);
        $movie->setReleaseDate($releaseDate);
        $movie->setCreatedBy($createdBy);

        return $movie;
    }

    public static function makeUser(
        string $uuid = 'a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6',
        string $password = '$2y$13$LHNa1MmodSVNdDGnwM0j0eBDBjmvn422gvuie8Z7grU1UOzsTPIDy',
        array $roles = [],
    ): User
    {
        $user = new User();
        $user->setUuid($uuid);
        $user->setPassword($password);
        $user->setRoles($roles);

        return $user;
    }

}