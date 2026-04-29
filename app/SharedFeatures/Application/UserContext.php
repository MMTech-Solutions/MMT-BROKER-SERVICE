<?php

namespace App\SharedFeatures\Application;

use App\SharedFeatures\DTOs\User;
use Illuminate\Support\Arr;

final class UserContext
{
    public function __construct(
        private readonly User $user,
    ) {}

    public function user(): User
    {
        return $this->user;
    }

    public function isAdmin(): bool
    {
        return in_array('admin', $this->user->roles);
    }

    public function adminCan(array|string $abilities): bool
    {
        return $this->isAdmin() && $this->can($abilities);
    }

    public function can(array|string $abilities): bool
    {
        if (is_string($abilities)) {
            return $this->canString($abilities);
        }

        foreach ($abilities as $category => $ability) {
            if (is_array($ability)) {
                if (! $this->checkIndexIsPresent($category, $ability)) {
                    return false;
                }
            } else {
                if (! in_array($ability, $this->abilitiesListAtPath((string) $category))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param  string  $path  Dot path (e.g. "platform" or "platform.server")
     */
    private function abilitiesListAtPath(string $path): array
    {
        $value = Arr::get($this->user->abilities, $path);

        return is_array($value) ? $value : [];
    }

    private function canString(string $abilities): bool
    {
        if (! str_contains($abilities, '.')) {
            return array_key_exists($abilities, $this->user->abilities);
        }

        $segments = explode('.', $abilities);
        $last = array_pop($segments);
        $prefix = implode('.', $segments);

        if ($prefix === '') {
            return false;
        }

        return in_array($last, $this->abilitiesListAtPath($prefix));
    }

    private function checkIndexIsPresent(string $index, array $haystack): bool
    {
        $list = $this->abilitiesListAtPath($index);

        foreach ($haystack as $item) {
            if (! in_array($item, $list)) {
                return false;
            }
        }

        return true;
    }

    public function id(): string
    {
        return $this->user->id;
    }
}
