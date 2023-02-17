<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsDecorator(OmdbGateway::class)]
class CacheableOmdbGateway
{
    public function __construct(
        #[MapDecorated]
        private OmdbGateway $actualGateway,
        private CacheInterface $cache,
    )
    {
    }

    public function getMovieRating(Movie $movie): float
    {
        $cacheKey = 'omdb_rating_' . $movie->getTitle();

        return $this->cache->get($cacheKey, function (CacheItemInterface $cacheItem) use ($movie) {
            $cacheItem->expiresAfter(10);
            return $this->actualGateway->getMovieRating($movie);
        });
    }
}