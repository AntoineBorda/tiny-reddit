<?php

namespace App\Entity;

use App\Repository\ExpressionRepository;
use App\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExpressionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Expression
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        type: Types::INTEGER
    )]
    private ?int $id = null;

    #[ORM\Column(
        type: Types::TEXT
    )]
    #[Assert\NotBlank(
        message: 'Merci de renseigner une proposition.',
    )]
    #[Assert\Length(
        min: 3,
        max: 500,
        minMessage: 'La proposition doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'La proposition doit contenir au maximum {{ limit }} caractères.',
    )]
    private ?string $expression = null;

    #[ORM\Column(
        type: Types::STRING,
        length: 255,
        nullable: true
    )]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'L\'auteur doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'L\'auteur doit contenir au maximum {{ limit }} caractères.',
    )]
    private ?string $author = null;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: true
    )]
    private ?bool $is_validate = null;

    #[ORM\Column(
        type: Types::BOOLEAN,
        nullable: true
    )]
    private ?bool $is_invalidate = null;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: true
    )]
    #[Assert\PositiveOrZero(
        message: 'Le nombre de votes ne peut pas être négatif.'
    )]
    private ?int $upvote = null;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: true
    )]
    #[Assert\PositiveOrZero(
        message: 'Le nombre de votes ne peut pas être négatif.'
    )]
    private ?int $downvote = null;

    #[ORM\Column(
        type: Types::STRING,
        length: 255
    )]
    private ?string $slug = null;

    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(
        inversedBy: 'expressions'
    )]
    #[ORM\JoinColumn(
        nullable: false
    )]
    private ?User $publisher = null;

    #[ORM\OneToMany(
        mappedBy: 'expression',
        targetEntity: UserExpression::class
    )]
    private Collection $userExpressions;

    #[ORM\OneToMany(
        mappedBy: 'expression',
        targetEntity: Comment::class,
    )]
    private Collection $comments;

    public function __construct()
    {
        $this->userExpressions = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): static
    {
        $this->expression = $expression;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isIsValidate(): ?bool
    {
        return $this->is_validate;
    }

    public function setIsValidate(bool $is_validate): static
    {
        $this->is_validate = $is_validate;

        return $this;
    }

    public function isIsInvalidate(): ?bool
    {
        return $this->is_invalidate;
    }

    public function setIsInvalidate(bool $is_invalidate): static
    {
        $this->is_invalidate = $is_invalidate;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): static
    {
        $this->publisher = $publisher;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if (!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return Collection<int, UserExpression>
     */
    public function getUserExpressions(): Collection
    {
        return $this->userExpressions;
    }

    public function addUserExpression(UserExpression $userExpression): static
    {
        if (!$this->userExpressions->contains($userExpression)) {
            $this->userExpressions->add($userExpression);
            $userExpression->setExpression($this);
        }

        return $this;
    }

    public function removeUserExpression(UserExpression $userExpression): static
    {
        if ($this->userExpressions->removeElement($userExpression)) {
            // set the owning side to null (unless already changed)
            if ($userExpression->getExpression() === $this) {
                $userExpression->setExpression(null);
            }
        }

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
            $comment->setExpression($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getExpression() === $this) {
                $comment->setExpression(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->expression;
    }

    public function getUpvote(): ?int
    {
        return $this->upvote;
    }

    public function setUpvote(?int $upvote): static
    {
        $this->upvote = $upvote;

        return $this;
    }

    public function getDownvote(): ?int
    {
        return $this->downvote;
    }

    public function setDownvote(?int $downvote): static
    {
        $this->downvote = $downvote;

        return $this;
    }
}
