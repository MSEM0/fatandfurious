<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietRepository::class)]
class Diet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $date = null;

    #[ORM\Column(nullable: true)]
    private ?array $breakfast;

    #[ORM\Column(length: 255, nullable: true)]
    private ?array $dinner;

    #[ORM\Column(length: 255, nullable: true)]
    private ?array $supper;

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getBreakfast(): ?array
    {
        return $this->breakfast;
    }

    public function setBreakfast(?array $breakfast): static
    {
        $this->breakfast = $breakfast;

        return $this;
    }

    public function getDinner(): ?array
    {
        return $this->dinner;
    }

    public function setDinner(?array $dinner): static
    {
        $this->dinner = $dinner;

        return $this;
    }

    public function getSupper(): ?array
    {
        return $this->supper;
    }

    public function setSupper(?array $supper): static
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
