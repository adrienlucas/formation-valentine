<?php
declare(strict_types=1);

namespace App\UseCase\Event;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

class MovieCreatedEvent extends Event
{
    public function __construct(
        public readonly Movie $movie
    )
    {
    }
}