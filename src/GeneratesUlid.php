<?php

namespace Dyrynda\Database\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Symfony\Component\Uid\Ulid;

/**
 * Ulid generation trait.
 *
 * Include this trait in any Eloquent model where you wish to automatically set
 * a Ulid field. When saving, if the Ulid field has not been set, generate a
 * new Ulid value, which will be set on the model and saved by Eloquent.
 *
 * @copyright 2023 atymicq@gmail.com
 * @license   MIT
 **
 * @method static \Illuminate\Database\Eloquent\Builder whereUlid(string $ulid)
 */
trait GeneratesUlid
{
    /**
     * Boot the trait, adding a creating observer.
     *
     * When persisting a new model instance, we resolve the Ulid field, then set
     * a fresh Ulid, taking into account if we need to cast to binary or not.
     */
    public static function bootGeneratesUlid(): void
    {
        static::creating(function ($model) {
            /* @var \Illuminate\Database\Eloquent\Model|static $model */

            foreach ($model->ulidColumns() as $item) {
                $ulid = new Ulid();

                if (isset($model->attributes[$item]) && !is_null($model->attributes[$item])) {
                    try {
                        $ulid = Ulid::fromString(strtolower($model->attributes[$item]));
                    } catch (InvalidUuidStringException $e) {
                        $ulid = Ulid::fromBinary($model->attributes[$item]);
                    }
                }

                $model->{$item} = strtolower($ulid->toBase32());
            }
        });
    }

    /**
     * The name of the column that should be used for the Ulid.
     */
    public function ulidColumn(): string
    {
        return 'ulid';
    }

    /**
     * The names of the columns that should be used for the Ulid.
     */
    public function ulidColumns(): array
    {
        return [$this->ulidColumn()];
    }

    /**
     * Scope queries to find by Ulid.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $ulid
     * @param string                                $ulidColumn
     */
    public function scopeWhereUlid($query, $ulid, $ulidColumn = null): Builder
    {
        $ulidColumn = !is_null($ulidColumn) && in_array($ulidColumn, $this->ulidColumns())
            ? $ulidColumn
            : $this->ulidColumns()[0];

        $ulid = $this->normaliseUuids($ulid);

        if ($this->isClassCastable($ulidColumn)) {
            $ulid = $this->bytesFromUlid($ulid);
        }

        return $query->whereIn(
            $this->qualifyColumn($ulidColumn),
            Arr::wrap($ulid)
        );
    }

    /**
     * Convert a single UUID or array of UUIDs to bytes.
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array|string $ulid
     */
    protected function bytesFromUlid($ulid): array
    {
        if (is_array($ulid) || $ulid instanceof Arrayable) {
            array_walk($ulid, function (&$uuid) {
                $uuid = Ulid::fromString($uuid)->toBinary();
            });

            return $ulid;
        }

        return Arr::wrap(Ulid::fromString($ulid)->toBinary());
    }

    /**
     * Normalises a single or array of input Ulids, filtering any invalid Ulid.
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array|string $ulid
     */
    protected function normaliseUuids($ulid): array
    {
        $ulid = array_map(function ($uuid) {
            return Str::lower($uuid);
        }, Arr::wrap($ulid));

        return array_filter($ulid, function ($uuid) {
            return Ulid::isValid($uuid);
        });
    }
}
