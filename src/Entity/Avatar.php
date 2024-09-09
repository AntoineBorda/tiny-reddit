<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
#[Vich\Uploadable]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        type: Types::INTEGER
    )]
    private ?int $id = null;

    #[Vich\UploadableField(
        mapping: 'img_avatars',
        fileNameProperty: 'name',
        size: 'size',
    )]
    #[Assert\File(
        maxSize: '2M',
        maxSizeMessage: 'Le fichier ne doit pas dépasser 2 Mo.',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        mimeTypesMessage: 'Le fichier doit être au format JPG, PNG, GIF, WEBP.',
    )]
    #[Assert\NotBlank(
        message: 'Merci de selectionner une image pour ton avatar.',
    )]
    private ?File $avatarFile = null;

    #[ORM\Column(
        type: Types::STRING,
        length: 255,
        nullable: true
    )]
    private ?string $name = null;

    #[ORM\Column(
        type: Types::INTEGER,
        nullable: true
    )]
    private ?int $size = null;

    #[ORM\Column(
        type: Types::DATETIME_IMMUTABLE,
        nullable: true
    )]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(
        cascade: ['persist', 'remove']
    )]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(File $avatarFile = null): static
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function __toString(): string
    {
        return $this->getName();
    }
}
