<?php

namespace Dyrynda\Database\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
 * @property string $uuidVersion
 *
 * @method static \Illuminate\Database\Eloquent\Builder whereUuid(string|string[] $uuid, ?string $uuidColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder whereNotUuid(string|string[] $uuid, ?string $uuidColumn = null)
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
        'uuid4',
        'uuid6',
        'uuid7',
        'ordered',
    ];

    /**
     * Boot the trait, adding a creating observer.
     *
     * When persisting a new model instance, we resolve the UUID field, then set
     * a fresh UUID, taking into account if we need to cast to binary or not.
     */
    public static function bootGeneratesUuid(): void
    {
        static::creating(function ($model) {
            foreach ($model->uuidColumns() as $item) {
                /* @var \Illuminate\Database\Eloquent\Model|static $model */
                $uuid = $model->resolveUuid();

                if (isset($model->attributes[$item]) && ! is_null($model->attributes[$item])) {
                    /* @var \Ramsey\Uuid\Uuid $uuid */
                    try {
                        $uuid = Uuid::fromString(strtolower($model->attributes[$item]));
                    } catch (InvalidUuidStringException $e) {
                        $uuid = Uuid::fromBytes($model->attributes[$item]);
                    }
                }

                $model->{$item} = strtolower($uuid->toString());
            }
        });
    }

    /**
     * The name of the column that should be used for the UUID.
     */
    public function uuidColumn(): string
    {
        return config('model-uuid.column_name', 'uuid');
    }

    /**
     * The names of the columns that should be used for the UUID.
     */
    public function uuidColumns(): array
    {
        return [$this->uuidColumn()];
    }

    /**
     * Resolve a UUID instance for the configured version.
     */
    public function resolveUuid(): UuidInterface
    {
        return call_user_func([Uuid::class, $this->resolveUuidVersion()]);
    }

    public function uuidVersion(): string
    {
        return 'uuid4';
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     */
    public function resolveUuidVersion(): string
    {
        if (($uuidVersion = $this->uuidVersion()) === 'ordered') {
            $uuidVersion = 'uuid6';
        }

        if (in_array($uuidVersion, $this->uuidVersions)) {
            return $uuidVersion;
        }

        return 'uuid4';
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $uuid
     * @param  string  $uuidColumn
     */
    public function scopeWhereUuid($query, $uuid, $uuidColumn = null): Builder
    {
        $uuidColumn = $this->getUuidColumn($uuidColumn);
        $uuid = $this->prepareUuid($uuid, $uuidColumn);

        return $query->whereIn(
            $this->qualifyColumn($uuidColumn),
            Arr::wrap($uuid)
        );
    }

    /**
     * Scope queries to find by UUID.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|array  $uuid
     * @param  string  $uuidColumn
     */
    public function scopeWhereNotUuid($query, $uuid, $uuidColumn = null): Builder
    {
        $uuidColumn = $this->getUuidColumn($uuidColumn);
        $uuid = $this->prepareUuid($uuid, $uuidColumn);

        return $query->whereNotIn(
            $this->qualifyColumn($uuidColumn),
            Arr::wrap($uuid)
        );
    }

    /**
     * Convert a single UUID or array of UUIDs to bytes.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array|string  $uuid
     */
    protected function bytesFromUuid($uuid): array
    {
        if (is_array($uuid) || $uuid instanceof Arrayable) {
            array_walk($uuid, function (&$uuid) {
                $uuid = Uuid::fromString($uuid)->getBytes();
            });

            return $uuid;
        }

        return Arr::wrap(Uuid::fromString($uuid)->getBytes());
    }

    /**
     * Normalises a single or array of input UUIDs, filtering any invalid UUIDs.
     *
     * @param  \Illuminate\Contracts\Support\Arrayable|array|string  $uuid
     */
    protected function normaliseUuids($uuid): array
    {
        $uuid = array_map(function ($uuid) {
            return Str::lower($uuid);
        }, Arr::wrap($uuid));

        $uuid = array_filter($uuid, function ($uuid) {
            return Uuid::isValid($uuid);
        });

        return $uuid;
    }

    /**
     * Guess UUID column based on model configurations or given uuid column
     *
     * @param  ?string  $uuidColumn
     */
    protected function getUuidColumn($uuidColumn = null): string
    {
        return ! is_null($uuidColumn) && in_array($uuidColumn, $this->uuidColumns())
            ? $uuidColumn
            : $this->uuidColumns()[0];
    }

    /**
     * Prepare UUID by normalization
     *
     * @param  string|array  $uuid
     * @param  string  $uuidColumn
     */
    protected function prepareUuid($uuid, $uuidColumn): array|string
    {
        $uuid = $this->normaliseUuids($uuid);

        if ($this->isClassCastable($uuidColumn)) {
            $uuid = $this->bytesFromUuid($uuid);
        }

        return $uuid;
    }
}
