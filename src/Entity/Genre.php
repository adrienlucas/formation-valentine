<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[UniqueEntity(['name'])]
#[ApiResource]
#[GetCollection]
#[Get]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cli', 'web'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cli', 'web'])]
    #[NotBlank]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, inversedBy: 'genres')]
    #[Ignore]
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): void
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
        }
    }

    public function removeMovie(Movie $movie): void
    {
        $this->movies->removeElement($movie);
    }

//    #[Callback]
//    public function hasAtLeastOneOfThree($violationList)
//    {
//        if($this->truc === null && $this->machin === null && $this->bidule === null) {
//            $violationList->add('You must provide at least one of truc, machin or bidule');
//        }
//    }
    public function setMovies(array $array)
    {
        $this->movies = new ArrayCollection($array);
    }
}
