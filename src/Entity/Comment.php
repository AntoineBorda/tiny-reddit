<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        type: Types::INTEGER
    )]
    private ?int $id = null;

    #[ORM\Column(
        type: Types::TEXT,
    )]
    #[Assert\NotBlank(
        message: 'Ton commentaire ne peut pas être vide.'
    )]
    #[Assert\Length(
        min: 2,
        minMessage: 'Ton commentaire doit contenir au moins {{ limit }} caractères.',
        max: 500,
        maxMessage: 'Ton commentaire ne peut pas contenir plus de {{ limit }} caractères.'
    )]
    private ?string $content = null;

    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(
        inversedBy: 'comments',
    )]
    private ?Expression $expression = null;

    #[ORM\ManyToOne(
        inversedBy: 'comments',
    )]
    private ?User $reader = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

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

    public function getExpression(): ?Expression
    {
        return $this->expression;
    }

    public function setExpression(?Expression $expression): static
    {
        $this->expression = $expression;

        return $this;
    }

    public function getReader(): ?User
    {
        return $this->reader;
    }

    public function setReader(?User $reader): static
    {
        $this->reader = $reader;

        return $this;
    }
}
