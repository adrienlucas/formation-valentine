<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

trait FixtureRelatedTrait
{
    private ReferenceRepository $fixtureReferences;

    private function loadFixtures(?FixtureInterface $fixture = null): void
    {
        if(!$this instanceof KernelTestCase) {
            throw new \RuntimeException('This trait can only be used in KernelTestCase classes.');
        }

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        $purger = new ORMPurger();
        $executor = new ORMExecutor($entityManager, $purger);

        $executor->execute($fixture === null ? [] : [$fixture]);
        $this->fixtureReferences = $executor->getReferenceRepository();
    }
}