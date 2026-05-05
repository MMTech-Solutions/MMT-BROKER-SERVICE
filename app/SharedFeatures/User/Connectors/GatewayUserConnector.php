<?php

namespace App\SharedFeatures\User\Connectors;

use App\SharedFeatures\User\User;
use App\SharedFeatures\User\UserConnectorInterface;
use Mmtech\Rbac\Auth\GatewayUser;

class GatewayUserConnector implements UserConnectorInterface
{
    public function __construct(
        private readonly GatewayUser $user,
    ){}

    public function user(): User
    {
        $firstName = $this->user->gatewayUserInfo['name'];
        $lastName = $this->user->gatewayUserInfo['lastname'] ?? '';
        
        return new User(
            $this->user->getAuthIdentifier(),
            $firstName,
            $lastName,
            $this->user->gatewayUserInfo['email'],
        );
    }

    public function can(array|string $abilities): bool
    {
        return $this->user->can($abilities);
    }
}