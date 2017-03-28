<?php

namespace Dyrynda\Database\Support;

/**
 * UUID generation trait.
 *
 * Include this trait in any Eloquent model where you wish to automatically set
 * a UUID field. When saving, if the UUID field has not been set, generate a
 * new UUID value, which will be set on the model and saved by Eloquent.
 *
 * @copyright 2017 Michael Dyrynda
 * @author    Michael Dyrynda <michael@dyrynda.com.au>
 * @license   MIT
 */
trait GeneratesUuid
{
    protected $uuidVersions = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
    ];

    public static function bootGeneratesUuid()
    {
        /**
         * Boot the traid, adding a creating observer.
         *
         * When persisting a new model instance, we resolve the UUID field, then set
         * a fresh UUID
         */
        static::creating(function ($model) {
            $uuidField = $model->resolveUuidField();
            $uuidVersion = $model->resolveUuidVersion();

            if (! $model->{$uuidField}) {
                $model->{$uuidField} = call_user_func("\Ramsey\Uuid\Uuid::{$uuidVersion}")->toString();
            }
        });
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     *
     * @return string
     */
    public function resolveUuidVersion()
    {
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions)) {
            return $this->uuidVersion;
        }

        return 'uuid4';
    }

    /**
     * Resolve the name of the field we should use for UUIDs.
     *
     * @return string
     */
    public function resolveUuidField()
    {
        return property_exists($this, 'uuidField') ? $this->uuidField : 'uuid';
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $uuid
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereUuid($query, $uuid)
    {
        return $query->where($this->resolveUuidField(), $uuid);
    }
}
