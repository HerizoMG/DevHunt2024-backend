<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $appreciation = null;

    #[ORM\Column(length: 50)]
    private ?string $Designation = null;

    #[ORM\ManyToOne(inversedBy: 'comment')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: PiecesJointes::class, inversedBy: 'comments')]
    private Collection $piecesJointes;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Post $post = null;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppreciation(): ?int
    {
        return $this->appreciation;
    }

    public function setAppreciation(?int $appreciation): static
    {
        $this->appreciation = $appreciation;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->Designation;
    }

    public function setDesignation(string $Designation): static
    {
        $this->Designation = $Designation;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PiecesJointes>
     */
    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
    }

    public function addPiecesJointe(PiecesJointes $piecesJointe): static
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes->add($piecesJointe);
        }

        return $this;
    }

    public function removePiecesJointe(PiecesJointes $piecesJointe): static
    {
        $this->piecesJointes->removeElement($piecesJointe);

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
