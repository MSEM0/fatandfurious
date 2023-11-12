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

    #[ORM\Column(nullable: true)]
    private ?int $totalFats = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalCarbons = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalProteins = null;

    #[ORM\Column(nullable: true)]
    private ?int $extraMealsF = null;

    #[ORM\Column(nullable: true)]
    private ?int $extraMealsC = null;

    #[ORM\Column(nullable: true)]
    private ?int $extraMealsP = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extraMealsComment = null;

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

    public function getTotalFats(): ?int
    {
        return $this->totalFats;
    }

    public function setTotalFats(?int $totalFats): static
    {
        $this->totalFats = $totalFats;

        return $this;
    }

    public function getTotalCarbons(): ?int
    {
        return $this->totalCarbons;
    }

    public function setTotalCarbons(?int $totalCarbons): static
    {
        $this->totalCarbons = $totalCarbons;

        return $this;
    }

    public function getTotalProteins(): ?int
    {
        return $this->totalProteins;
    }

    public function setTotalProteins(?int $totalProteins): static
    {
        $this->totalProteins = $totalProteins;

        return $this;
    }

    public function getExtraMealsF(): ?int
    {
        return $this->extraMealsF;
    }

    public function setExtraMealsF(?int $extraMealsF): static
    {
        $this->extraMealsF = $extraMealsF;

        return $this;
    }

    public function getExtraMealsC(): ?int
    {
        return $this->extraMealsC;
    }

    public function setExtraMealsC(?int $extraMealsC): static
    {
        $this->extraMealsC = $extraMealsC;

        return $this;
    }

    public function getExtraMealsP(): ?int
    {
        return $this->extraMealsP;
    }

    public function setExtraMealsP(?int $extraMealsP): static
    {
        $this->extraMealsP = $extraMealsP;

        return $this;
    }

    public function getExtraMealsComment(): ?string
    {
        return $this->extraMealsComment;
    }

    public function setExtraMealsComment(?string $extraMealsComment): static
    {
        $this->extraMealsComment = $extraMealsComment;

        return $this;
    }
}
