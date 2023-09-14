<?php

namespace Dyrynda\Database\Support;

use Illuminate\Database\Eloquent\Model;

trait BindsOnUlid
{
    /**
     * Route bind desired uuid field
     * Default 'uuid' column name has been set.
     *
     * @param  string  $value
     * @param  null|string  $field
     */
    public function resolveRouteBinding($value, $field = null): Model
    {
        return self::whereUlid($value, $field)->firstOrFail();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return $this->ulidColumn();
    }
}
