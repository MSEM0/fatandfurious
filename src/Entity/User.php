<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Diet::class)]
    private Collection $dailyDietKcal;

    #[ORM\Column]
    private ?int $medSatisfaction = null;

    #[ORM\Column]
    private ?int $minKcal = null;

    #[ORM\Column]
    private ?int $maxKcal = null;

    public function __construct()
    {
        $this->dailyDietKcal = new ArrayCollection();
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
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Diet>
     */
    public function getDailyDietKcal(): Collection
    {
        return $this->dailyDietKcal;
    }

    public function addDailyDietKcal(Diet $dailyDietKcal): static
    {
        if (!$this->dailyDietKcal->contains($dailyDietKcal)) {
            $this->dailyDietKcal->add($dailyDietKcal);
            $dailyDietKcal->setUser($this);
        }

        return $this;
    }

    public function removeDailyDietKcal(Diet $dailyDietKcal): static
    {
        if ($this->dailyDietKcal->removeElement($dailyDietKcal)) {
            // set the owning side to null (unless already changed)
            if ($dailyDietKcal->getUser() === $this) {
                $dailyDietKcal->setUser(null);
            }
        }

        return $this;
    }

    public function getMedSatisfaction(): ?int
    {
        return $this->medSatisfaction;
    }

    public function setMedSatisfaction(int $medSatisfaction): static
    {
        $this->medSatisfaction = $medSatisfaction;

        return $this;
    }

    public function getMinKcal(): ?int
    {
        return $this->minKcal;
    }

    public function setMinKcal(int $minKcal): static
    {
        $this->minKcal = $minKcal;

        return $this;
    }

    public function getMaxKcal(): ?int
    {
        return $this->maxKcal;
    }

    public function setMaxKcal(int $maxKcal): static
    {
        $this->maxKcal = $maxKcal;

        return $this;
    }
}
