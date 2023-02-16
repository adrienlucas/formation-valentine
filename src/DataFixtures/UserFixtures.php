<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);
        $user->setUuid(Uuid::v4());
        $manager->persist($user);

        $user = new User();
        $hashedPassword = $this->hasher->hashPassword($user, 'user');
        $user->setPassword($hashedPassword);
        $user->setUuid(Uuid::v4());
        $manager->persist($user);

        $manager->flush();
    }
}
