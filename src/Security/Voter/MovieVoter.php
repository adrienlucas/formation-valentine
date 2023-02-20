<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Gateway\OmdbGateway;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'MOVIE_EDIT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT
            && $subject instanceof Movie;
    }

    /**
     * @param Movie $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return $user->isEqualTo($subject->getCreatedBy());
    }
}
