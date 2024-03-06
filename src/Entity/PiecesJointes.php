<?php

namespace App\Entity;

use App\Repository\PiecesJointesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PiecesJointesRepository::class)]
class PiecesJointes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link = null;

    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'piecesJointes')]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'piecesJointes')]
    private Collection $post;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

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
            $comment->addPiecesJointe($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            $comment->removePiecesJointe($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPost(): Collection
    {
        return $this->post;
    }

    public function addPost(Post $post): static
    {
        if (!$this->post->contains($post)) {
            $this->post->add($post);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        $this->post->removeElement($post);

        return $this;
    }
}
