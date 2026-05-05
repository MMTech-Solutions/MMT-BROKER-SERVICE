<?php

namespace App\SharedFeatures\User;

class UserContext
{
    public function __construct(
        private readonly UserConnectorInterface $userConnector,
    ) {}

    public function userInfo(): User
    {
        return $this->userConnector->user();
    }

    public function can(array|string $abilities): bool
    {
        return $this->userConnector->can($abilities);
    }
}