<?php

namespace App\SharedFeatures\User;

interface UserConnectorInterface
{
    public function user(): User;
    
    public function can(array|string $abilities): bool;
}