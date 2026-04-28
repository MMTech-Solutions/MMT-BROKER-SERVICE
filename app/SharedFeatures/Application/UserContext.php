<?php

namespace App\SharedFeatures\Application;

use App\SharedFeatures\DTOs\User;

final class UserContext
{
    public function __construct(
        private readonly User $user,
    ) {}

    public function user(): User
    {
        return $this->user;
    }

    public function can(string $permission): bool
    {
        return true;
    }

    public function cannot(string $permission): bool
    {
        return false;
    }

    public function id(): string
    {
        return $this->user->id;
    }
}