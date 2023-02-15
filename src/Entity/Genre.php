<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cli', 'web'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['cli', 'web'])]
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
}
