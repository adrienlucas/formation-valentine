<?php

namespace App\EventSubscriber;

use App\UseCase\Event\MovieCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    public function onMovieCreatedEvent(MovieCreatedEvent $event): void
    {
//        $event->movie
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieCreatedEvent::class => 'onMovieCreatedEvent',
        ];
    }
}
