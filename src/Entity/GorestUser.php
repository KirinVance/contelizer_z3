<?php

namespace App\Entity;

use App\Repository\GorestUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GorestUserRepository::class)]
class GorestUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $gorest_id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGorestId(): ?int
    {
        return $this->gorest_id;
    }

    public function setGorestId(int $gorest_id): self
    {
        $this->gorest_id = $gorest_id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
