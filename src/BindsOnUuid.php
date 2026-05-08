<?php

namespace Dyrynda\Database\Support;

use Illuminate\Database\Eloquent\Model;

trait BindsOnUuid
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
        if (! is_null($field) && ! in_array($field, $this->uuidColumns())) {
            return parent::resolveRouteBinding($value, $field);
        }

        return self::whereUuid($value, $field)->firstOrFail();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return $this->uuidColumn();
    }
}
