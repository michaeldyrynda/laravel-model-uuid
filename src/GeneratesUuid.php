<?php

namespace Dyrynda\Database\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

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
 *
 * @property  string  $uuidVersion
 */
trait GeneratesUuid
{
    /**
     * The UUID versions.
     *
     * @var array
     */
    protected $uuidVersions = [
        'uuid1',
        'uuid3',
        'uuid4',
        'uuid5',
        'ordered',
    ];

    /**
     * Determine whether an attribute should be cast to a native type.
     *
     * @param  string  $key
     * @param  array|string|null  $types
     * @return bool
     */
    abstract public function hasCast($key, $types = null);

    /**
     * Boot the trait, adding a creating observer.
     *
     * When persisting a new model instance, we resolve the UUID field, then set
     * a fresh UUID, taking into account if we need to cast to binary or not.
     *
     * @return void
     */
    public static function bootGeneratesUuid(): void
    {
        static::creating(function ($model) {
            foreach ($model->uuidColumns() as $item) {
                /* @var \Illuminate\Database\Eloquent\Model|static $model */
                $uuid = $model->resolveUuid();

                if (isset($model->attributes[$item]) && ! is_null($model->attributes[$item])) {
                    /* @var \Ramsey\Uuid\Uuid $uuid */
                    $uuid = $uuid->fromString(strtolower($model->attributes[$item]));
                }

                $model->{$item} = strtolower($uuid->toString());
            }
        });
    }

    /**
     * The name of the column that should be used for the UUID.
     *
     * @return string
     */
    public function uuidColumn(): string
    {
        return 'uuid';
    }

    /**
     * The names of the columns that should be used for the UUID.
     *
     * @return array
     */
    public function uuidColumns(): array
    {
        return [$this->uuidColumn()];
    }

    /**
     * Resolve a UUID instance for the configured version.
     *
     * @return \Ramsey\Uuid\Uuid
     */
    public function resolveUuid(): Uuid
    {
        if (($version = $this->resolveUuidVersion()) == 'ordered') {
            return Str::orderedUuid();
        }

        return call_user_func([Uuid::class, $version]);
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     *
     * @return string
     */
    public function resolveUuidVersion(): string
    {
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions)) {
            return $this->uuidVersion;
        }

        return 'uuid4';
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $uuid
     * @param  string  $uuidColumn
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereUuid($query, $uuid, $uuidColumn = null): Builder
    {
        $uuidColumn = ! is_null($uuidColumn) && in_array($uuidColumn, $this->uuidColumns())
            ? $uuidColumn
            : $this->uuidColumns()[0];

        return $query->whereIn($uuidColumn, array_map(function ($uuid) {
            return strtolower($uuid);
        }, Arr::wrap($uuid)));
    }

    /**
     * Convert a single UUID or array of UUIDs to bytes.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array|string  $uuid
     * @return array
     */
    protected function bytesFromUuid($uuid): array
    {
        if (is_array($uuid) || $uuid instanceof Arrayable) {
            array_walk($uuid, function (&$uuid) {
                $uuid = $this->resolveUuid()->fromString($uuid)->getBytes();
            });

            return $uuid;
        }

        return Arr::wrap($this->resolveUuid()->fromString($uuid)->getBytes());
    }
}
