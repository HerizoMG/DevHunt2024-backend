<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 150 ,nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $raisonSocial = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isAdmin = false;

    #[ORM\Column]
    private ?bool $isNovice = false;

    #[ORM\Column]
    private ?bool $isEnseignant = false;

    #[ORM\Column]
    private ?bool $isAdministration = false;

    #[ORM\Column]
    private ?bool $isEntreprise = false;

    #[ORM\Column]
    private ?bool $isMateriel = false;

    #[ORM\Column]
    private ?bool $isElder = false;

    #[ORM\Column]
    private ?bool $isImmobilier = false;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comment;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'users')]
    private Collection $liker;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user')]
    private Collection $post;

    #[ORM\ManyToMany(targetEntity: Matiere::class, inversedBy: 'users')]
    private Collection $matiere;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->liker = new ArrayCollection();
        $this->post = new ArrayCollection();
        $this->matiere = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->username;
    }

    public function setUserName(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(?string $raisonSocial): static
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function isIsNovice(): ?bool
    {
        return $this->isNovice;
    }

    public function setIsNovice(bool $isNovice): static
    {
        $this->isNovice = $isNovice;

        return $this;
    }

    public function isIsEnseignant(): ?bool
    {
        return $this->isEnseignant;
    }

    public function setIsEnseignant(bool $isEnseignant): static
    {
        $this->isEnseignant = $isEnseignant;

        return $this;
    }

    public function isIsAdministration(): ?bool
    {
        return $this->isAdministration;
    }

    public function setIsAdministration(bool $isAdministration): static
    {
        $this->isAdministration = $isAdministration;

        return $this;
    }

    public function isIsEntreprise(): ?bool
    {
        return $this->isEntreprise;
    }

    public function setIsEntreprise(bool $isEntreprise): static
    {
        $this->isEntreprise = $isEntreprise;

        return $this;
    }

    public function isIsMateriel(): ?bool
    {
        return $this->isMateriel;
    }

    public function setIsMateriel(bool $isMateriel): static
    {
        $this->isMateriel = $isMateriel;

        return $this;
    }

    public function isIsElder(): ?bool
    {
        return $this->isElder;
    }

    public function setIsElder(bool $isElder): static
    {
        $this->isElder = $isElder;

        return $this;
    }

    public function isIsImmobilier(): ?bool
    {
        return $this->isImmobilier;
    }

    public function setIsImmobilier(bool $isImmobilier): static
    {
        $this->isImmobilier = $isImmobilier;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getLiker(): Collection
    {
        return $this->liker;
    }

    public function addLiker(Post $liker): static
    {
        if (!$this->liker->contains($liker)) {
            $this->liker->add($liker);
        }

        return $this;
    }

    public function removeLiker(Post $liker): static
    {
        $this->liker->removeElement($liker);

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
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatiere(): Collection
    {
        return $this->matiere;
    }

    public function addMatiere(Matiere $matiere): static
    {
        if (!$this->matiere->contains($matiere)) {
            $this->matiere->add($matiere);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): static
    {
        $this->matiere->removeElement($matiere);

        return $this;
    }
}
