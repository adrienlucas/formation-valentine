<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[UniqueEntity('title')]
#[ApiResource(
    normalizationContext: ['groups' => ['web']],
    paginationItemsPerPage: 3,
)]
#[ApiFilter(SearchFilter::class, properties: ['title'])]
#[ApiFilter(DateFilter::class, properties: ['releaseDate' => DateFilter::EXCLUDE_NULL])]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cli', 'web'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Length(max: 56)]
    #[NotBlank]
    #[Groups(['cli', 'web'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['cli', 'web'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['cli', 'web'])]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'movies')]
    #[Groups(['web'])]
    #[Valid]
    private Collection $genres;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['toto'])]
    private User $createdBy;

    #[Groups(['web'])]
    public float $rating;

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    #[SerializedName('genres')]
    #[Groups(['cli'])]
    public function getGenresNames(): string
    {
        return implode(', ', $this->genres->map(
            fn (Genre $genre) => $genre->getName())->toArray()
        );
    }

    public function __construct()
    {
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addMovie($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeMovie($this);
        }

        return $this;
    }

    public function setGenres(array $persistedGenres): void
    {
        $this->genres = new ArrayCollection($persistedGenres);
    }
}
