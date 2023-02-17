<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OmdbGateway
{
    public function __construct(
        private HttpClientInterface $client,
    )
    {
    }

    public function getMovieRating(Movie $movie): float
    {
        $apiResponse = $this->client->request('GET', 'http://www.omdbapi.com', [
            'query' => [
                'apikey' => 'e0ded5e2',
                't' => $movie->getTitle(),
            ],
        ]);

        return (float) $apiResponse->toArray()['imdbRating'] ?? 0;
    }

}