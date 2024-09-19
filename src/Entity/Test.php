<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $a = null;

    #[ORM\Column(length: 255)]
    private ?string $b = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getA(): ?string
    {
        return $this->a;
    }

    public function setA(?string $a): static
    {
        $this->a = $a;

        return $this;
    }

    public function getB(): ?string
    {
        return $this->b;
    }

    public function setB(string $b): static
    {
        $this->b = $b;

        return $this;
    }
}
