<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id] // clé primaire
    #[ORM\GeneratedValue] // auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'auhor')]
    private Collection $yes;

    #[ORM\Column]
    private ?int $nb_books = null;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Book $ye): static
    {
        if (!$this->yes->contains($ye)) {
            $this->yes->add($ye);
            $ye->setAuthor($this);
        }

        return $this;
    }

    public function removeYe(Book $ye): static
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getAuthor() === $this) {
                $ye->setAuthor(null);
            }
        }

        return $this;
    }

    public function getNbBooks(): ?int
    {
        return $this->nb_books;
    }

    public function setNbBooks(int $nb_books): static
    {
        $this->nb_books = $nb_books;

        return $this;
    }
    
}
