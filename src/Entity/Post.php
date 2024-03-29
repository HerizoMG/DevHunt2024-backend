<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isEpingle = null;

	#[ORM\ManyToMany(targetEntity: PiecesJointes::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
      	private Collection $piecesJointes;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'posts')]
    private Collection $tag;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'post')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Cours::class, mappedBy: 'post')]
    private Collection $cours;

    #[ORM\Column(length: 20)]
    private ?string $title = null;
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'post')]
    private Collection $liker;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;
    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
        $this->tag = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->liker = new ArrayCollection();
		$this->createdAt = new \DateTimeImmutable();
		$this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isIsEpingle(): ?bool
    {
        return $this->isEpingle;
    }

    public function setIsEpingle(bool $isEpingle): static
    {
        $this->isEpingle = $isEpingle;

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
            $piecesJointe->addPost($this);
        }

        return $this;
    }

    public function removePiecesJointe(PiecesJointes $piecesJointe): static
    {
        if ($this->piecesJointes->removeElement($piecesJointe)) {
            $piecesJointe->removePost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tag->contains($tag)) {
            $this->tag->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setPost($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getPost() === $this) {
                $cour->setPost(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLiker(): Collection
    {
        return $this->liker;
    }

    public function addLiker(Like $liker): static
    {
        if (!$this->liker->contains($liker)) {
            $this->liker->add($liker);
            $liker->setPost($this);
        }

        return $this;
    }

    public function removeLiker(Like $liker): static
    {
        if ($this->liker->removeElement($liker)) {
            // set the owning side to null (unless already changed)
            if ($liker->getPost() === $this) {
                $liker->setPost(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

}
