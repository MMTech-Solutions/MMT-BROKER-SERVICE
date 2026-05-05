<?php

namespace App\Features\Trading\Account\Actions;

use App\Features\Trading\Account\Exceptions\AccountExternalUserFilterForbiddenException;

/**
 * Resuelve el external_user_id efectivo para el listado de cuentas según permisos.
 * Devuelve null cuando el listado no debe filtrarse por owner (solo con account.read-all y sin external_user_id en query).
 */
class ResolveEffectiveExternalUserIdForAccountListAction
{
    public function execute(?string $requestedExternalUserId, bool $canReadAll, string $consumerId): ?string
    {
        if (! $canReadAll) {
            if ($requestedExternalUserId !== null) {
                throw new AccountExternalUserFilterForbiddenException();
            }

            return $consumerId;
        }

        return $requestedExternalUserId;
    }
}
