<?php

namespace Laravel\Lumen\Auth;

use Illuminate\Contracts\Auth\Access\Gate;

trait Authorizable
{
    /**
     * Determine if the entity has a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     *
     * @return bool
     */
    public function can(string $ability, $arguments = []): bool
    {
        return app(Gate::class)->forUser($this)->check($ability, $arguments);
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     *
     * @return bool
     */
    public function cant(string $ability, $arguments = []): bool
    {
        return ! $this->can($ability, $arguments);
    }

    /**
     * Determine if the entity does not have a given ability.
     *
     * @param  string  $ability
     * @param  array|mixed  $arguments
     *
     * @return bool
     */
    public function cannot(string $ability, $arguments = []): bool
    {
        return $this->cant($ability, $arguments);
    }
}
