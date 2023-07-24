<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class UserService extends AbstractController
{

    public function __construct(private readonly Security $security)
    {
    }

    public function getLoggedUser()
    {
        $user = $this->security->getUser();
    }
    public function getLoggedUsersEmail(): string|null
    {
        $user = $this->security->getUser();

        if ($user) {
            return $user->getEmail();
        }
        return null;
    }
}