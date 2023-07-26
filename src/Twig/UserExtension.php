<?php

declare(strict_types=1);

namespace App\Twig;

use App\Services\UserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('logged_users_email', [$this, 'getLoggedUsersEmail']),
        ];
    }

    public function getLoggedUsersEmail(): string|null
    {
        return $this->userService->getLoggedUsersEmail();
    }
}