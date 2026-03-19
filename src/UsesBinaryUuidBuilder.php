<?php

declare(strict_types=1);

namespace Dyrynda\Database\Support;

use Illuminate\Database\Query\Builder;

/**
 * Enables binary UUID relationship support.
 *
 * Use this trait on models that store UUIDs as BINARY(16) using
 * the EfficientUuid cast to enable automatic conversion in
 * Eloquent relationships.
 *
 * When to use this trait:
 * - Your model uses the EfficientUuid cast on UUID columns
 * - You need Eloquent relationships to work with binary UUID storage
 * - You're experiencing issues with belongsTo, hasMany, or other relationships
 *
 * Example usage:
 * ```php
 * use Dyrynda\Database\Support\{GeneratesUuid, UsesBinaryUuidBuilder};
 * use Dyrynda\Database\Support\Casts\EfficientUuid;
 *
 * class User extends Model
 * {
 *     use GeneratesUuid, UsesBinaryUuidBuilder;
 *
 *     public $incrementing = false;
 *     protected $keyType = 'string';
 *
 *     protected function casts(): array
 *     {
 *         return ['id' => EfficientUuid::class];
 *     }
 *
 *     public function posts()
 *     {
 *         return $this->hasMany(Post::class);
 *     }
 * }
 * ```
 */
trait UsesBinaryUuidBuilder
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * This method is called automatically by Eloquent when building queries.
     * It returns our custom BinaryUuidBuilder to handle UUID binary conversions.
     *
     * @param  Builder  $query
     * @return BinaryUuidBuilder<static>
     */
    public function newEloquentBuilder($query): BinaryUuidBuilder
    {
        return new BinaryUuidBuilder($query);
    }
}
