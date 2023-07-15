<?php

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietRepository::class)]
class Diet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $breakfast = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dinner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $supper = null;

    #[ORM\Column(nullable: true)]
    private ?int $extraMeals = null;

    #[ORM\Column]
    private ?int $kcal = null;

    #[ORM\ManyToOne(inversedBy: 'dailyDietKcal')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBreakfast(): ?string
    {
        return $this->breakfast;
    }

    public function setBreakfast(?string $breakfast): static
    {
        $this->breakfast = $breakfast;

        return $this;
    }

    public function getDinner(): ?string
    {
        return $this->dinner;
    }

    public function setDinner(?string $dinner): static
    {
        $this->dinner = $dinner;

        return $this;
    }

    public function getSupper(): ?string
    {
        return $this->supper;
    }

    public function setSupper(?string $supper): static
    {
        $this->supper = $supper;

        return $this;
    }

    public function getExtraMeals(): ?int
    {
        return $this->extraMeals;
    }

    public function setExtraMeals(?int $extraMeals): static
    {
        $this->extraMeals = $extraMeals;

        return $this;
    }

    public function getKcal(): ?int
    {
        return $this->kcal;
    }

    public function setKcal(int $kcal): static
    {
        $this->kcal = $kcal;

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
