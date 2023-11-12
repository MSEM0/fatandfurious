<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $kcal = null;

    #[ORM\Column]
    private ?int $satisfaction = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $ingredients = null;

    #[ORM\Column(nullable: true)]
    private ?bool $doublePortion = null;

    #[ORM\Column(nullable: true)]
    private ?int $fats = null;

    #[ORM\Column(nullable: true)]
    private ?int $carbons = null;

    #[ORM\Column(nullable: true)]
    private ?int $proteins = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getSatisfaction(): ?int
    {
        return $this->satisfaction;
    }

    public function setSatisfaction(int $satisfaction): static
    {
        $this->satisfaction = $satisfaction;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function isDoublePortion(): ?bool
    {
        return $this->doublePortion;
    }

    public function setDoublePortion(?bool $doublePortion): static
    {
        $this->doublePortion = $doublePortion;

        return $this;
    }

    public function getFats(): ?int
    {
        return $this->fats;
    }

    public function setFats(?int $fats): static
    {
        $this->fats = $fats;

        return $this;
    }

    public function getCarbons(): ?int
    {
        return $this->carbons;
    }

    public function setCarbons(?int $carbons): static
    {
        $this->carbons = $carbons;

        return $this;
    }

    public function getProteins(): ?int
    {
        return $this->proteins;
    }

    public function setProteins(?int $proteins): static
    {
        $this->proteins = $proteins;

        return $this;
    }
}
