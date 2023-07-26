<?php

declare(strict_types=1);

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserService extends AbstractController
{

    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getLoggedUsersEmail(): string|null
    {
        $user = $this->security->getUser();

        if ($user) {
            return $user->getEmail();
        }
        return null;
    }

    public function getUsersChoices(): array
    {
        $user = $this->security->getUser();
        $usersChoices = [
            'minKcal' => $user->getMinKcal(),
            'maxKcal' => $user->getMaxKcal(),
            'medSatisfaction' => $user->getMedSatisfaction(),
        ];
        return $usersChoices;
    }

    public function setUsersChoice(string $choice, int $newValue)
    {
        $user = $this->security->getUser();
        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($user, $choice, $newValue);
        $this -> entityManager->flush();
    }
}