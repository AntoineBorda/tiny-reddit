<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(
    fields: ['email'],
    message: 'Cet email est déjà utilisé.',
)]
#[UniqueEntity(
    fields: ['pseudo'],
    message: 'Ce pseudo est déjà utilisé.',
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        type: Types::INTEGER
    )]
    private ?int $id = null;

    #[ORM\Column(
        type: Types::STRING,
        length: 180,
        unique: true
    )]
    #[Assert\NotBlank(
        message: 'Merci de renseigner un email.',
    )]
    #[Assert\Email(
        message: 'Merci de renseigner un email valide.',
    )]
    #[Assert\Length(
        min: 6,
        max: 180,
        minMessage: 'L\'email doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'L\'email doit contenir au maximum {{ limit }} caractères.',
    )]
    private ?string $email = null;

    #[ORM\Column(
        type: 'json'
    )]
    private array $roles = [];

    #[Assert\NotBlank(
        message: 'Merci de renseigner un mot de passe.',
        groups: ['registration'],
    )]
    #[Assert\Length(
        min: 8,
        max: 255,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le mot de passe doit contenir au maximum {{ limit }} caractères.',
        groups: ['registration'],
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        message: 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
        groups: ['registration'],
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(
        type: Types::STRING,
    )]
    private ?string $password = null;

    #[ORM\Column(
        type: Types::STRING,
        length: 255
    )]
    #[Assert\NotBlank(
        message: 'Merci de renseigner un pseudo.',
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le pseudo doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le pseudo doit contenir au maximum {{ limit }} caractères.',
    )]
    private ?string $pseudo = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(
        nullable: true
    )]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(
        mappedBy: 'publisher',
        targetEntity: Expression::class
    )]
    private Collection $expressions;

    #[ORM\OneToMany(
        mappedBy: 'reader',
        targetEntity: UserExpression::class
    )]
    private Collection $userExpressions;

    #[ORM\OneToMany(
        mappedBy: 'reader',
        targetEntity: Comment::class
    )]
    private Collection $comments;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->userExpressions = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->expressions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Expression>
     */
    public function getExpressions(): Collection
    {
        return $this->expressions;
    }

    public function addExpression(Expression $expression): static
    {
        if (!$this->expressions->contains($expression)) {
            $this->expressions->add($expression);
            $expression->setPublisher($this);
        }

        return $this;
    }

    public function removeExpression(Expression $expression): static
    {
        if ($this->expressions->removeElement($expression)) {
            // set the owning side to null (unless already changed)
            if ($expression->getPublisher() === $this) {
                $expression->setPublisher(null);
            }
        }

        return $this;
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
            $userExpression->setReader($this);
        }

        return $this;
    }

    public function removeUserExpression(UserExpression $userExpression): static
    {
        if ($this->userExpressions->removeElement($userExpression)) {
            // set the owning side to null (unless already changed)
            if ($userExpression->getReader() === $this) {
                $userExpression->setReader(null);
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
            $comment->setReader($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getReader() === $this) {
                $comment->setReader(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->pseudo;
    }
}
