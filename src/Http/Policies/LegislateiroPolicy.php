<?php

namespace Legislateiro\Http\Policies;

use App\Models\User;

/**
 * Class LegislateiroPolicy.
 *
 * @package Finder\Http\Policies
 */
class LegislateiroPolicy
{
    /**
     * Create a legislateiro.
     *
     * @param  User   $authUser
     * @param  string $legislateiroClass
     * @return bool
     */
    public function create(User $authUser, string $legislateiroClass)
    {
        if ($authUser->toEntity()->isAdministrator()) {
            return true;
        }

        return false;
    }

    /**
     * Get a legislateiro.
     *
     * @param  User  $authUser
     * @param  mixed $legislateiro
     * @return bool
     */
    public function get(User $authUser, $legislateiro)
    {
        return $this->hasAccessToLegislateiro($authUser, $legislateiro);
    }

    /**
     * Determine if an authenticated user has access to a legislateiro.
     *
     * @param  User $authUser
     * @param  $legislateiro
     * @return bool
     */
    private function hasAccessToLegislateiro(User $authUser, $legislateiro): bool
    {
        if ($authUser->toEntity()->isAdministrator()) {
            return true;
        }

        if ($legislateiro instanceof User && $authUser->id === optional($legislateiro)->id) {
            return true;
        }

        if ($authUser->id === optional($legislateiro)->created_by_user_id) {
            return true;
        }

        return false;
    }

    /**
     * Update a legislateiro.
     *
     * @param  User  $authUser
     * @param  mixed $legislateiro
     * @return bool
     */
    public function update(User $authUser, $legislateiro)
    {
        return $this->hasAccessToLegislateiro($authUser, $legislateiro);
    }

    /**
     * Delete a legislateiro.
     *
     * @param  User  $authUser
     * @param  mixed $legislateiro
     * @return bool
     */
    public function delete(User $authUser, $legislateiro)
    {
        return $this->hasAccessToLegislateiro($authUser, $legislateiro);
    }
}
