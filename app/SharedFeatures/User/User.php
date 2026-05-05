<?php

namespace App\SharedFeatures\User;

/**
 * Objeto de usuario del sistema
 */
class User
{
    public readonly string $fullName;

    public function __construct(
        public readonly string $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ){
        $this->fullName = "{$this->firstName} {$this->lastName}";
    }
}