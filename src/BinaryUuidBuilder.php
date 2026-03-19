<?php

declare(strict_types=1);

namespace Dyrynda\Database\Support;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;

/**
 * Custom Eloquent Builder for models with binary UUID columns.
 *
 * This builder automatically converts UUID strings to binary format
 * when querying columns that use the EfficientUuid cast, ensuring
 * Eloquent relationships work correctly with binary UUID storage.
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class BinaryUuidBuilder extends Builder
{
    /**
     * Add a basic where clause to the query.
     *
     * Automatically converts UUID strings to binary when querying
     * columns that use the EfficientUuid cast.
     *
     * @param  \Closure|string|array<mixed>|Expression  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        // Check if this is a simple where clause on a UUID column
        if (is_string($column) && $this->shouldConvertToUuidBinary($column, $value ?? $operator)) {
            $actualValue = func_num_args() === 2 ? $operator : $value;

            if (is_string($actualValue) && Uuid::isValid($actualValue)) {
                // Convert UUID string to binary
                $binaryValue = Uuid::fromString($actualValue)->getBytes();

                if (func_num_args() === 2) {
                    return parent::where($column, '=', $binaryValue, $boolean);
                }

                return parent::where($column, $operator, $binaryValue, $boolean);
            }
        }

        return parent::where($column, $operator, $value, $boolean);
    }

    /**
     * Add a "where in" clause to the query.
     *
     * Automatically converts arrays of UUID strings to binary format.
     * This is crucial for eager loading (with()) and other bulk queries.
     *
     * @param  Expression|string  $column
     * @param  mixed  $values
     * @param  string  $boolean
     * @param  bool  $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        if (is_string($column) && $this->isUuidColumn($column)) {
            // Convert array of UUID strings to binary
            $values = collect($values)->map(function ($value) {
                if (is_string($value) && Uuid::isValid($value)) {
                    return Uuid::fromString($value)->getBytes();
                }

                return $value;
            })->all();
        }

        return parent::whereIn($column, $values, $boolean, $not);
    }

    /**
     * Add a "where not in" clause to the query.
     *
     * Automatically converts arrays of UUID strings to binary format.
     *
     * @param  Expression|string  $column
     * @param  mixed  $values
     * @param  string  $boolean
     * @return $this
     */
    public function whereNotIn($column, $values, $boolean = 'and')
    {
        return $this->whereIn($column, $values, $boolean, true);
    }

    /**
     * Add an "or where in" clause to the query.
     *
     * Ensures UUID binary conversion is applied when using orWhereIn,
     * which would otherwise bypass the Eloquent-level whereIn override.
     *
     * @param  Expression|string  $column
     * @param  mixed  $values
     * @return $this
     */
    public function orWhereIn($column, $values)
    {
        return $this->whereIn($column, $values, 'or');
    }

    /**
     * Add an "or where not in" clause to the query.
     *
     * Ensures UUID binary conversion is applied when using orWhereNotIn,
     * which would otherwise bypass the Eloquent-level whereIn override.
     *
     * @param  Expression|string  $column
     * @param  mixed  $values
     * @return $this
     */
    public function orWhereNotIn($column, $values)
    {
        return $this->whereIn($column, $values, 'or', true);
    }

    /**
     * Check if column should convert UUID string to binary.
     */
    protected function shouldConvertToUuidBinary(string $column, mixed $value): bool
    {
        return $this->isUuidColumn($column) && is_string($value) && Uuid::isValid($value);
    }

    /**
     * Check if a column uses UUID binary cast (EfficientUuid).
     */
    protected function isUuidColumn(string $column): bool
    {
        $model = $this->getModel();

        // Remove table prefix if present (e.g., 'users.id' -> 'id')
        $columnName = str_contains($column, '.')
            ? substr($column, strrpos($column, '.') + 1)
            : $column;

        // Get casts from the model
        $casts = $model->getCasts();

        if (! isset($casts[$columnName])) {
            return false;
        }

        $castType = $casts[$columnName];

        // Handle string class names
        if (is_string($castType)) {
            return $castType === Casts\EfficientUuid::class
                || $castType === 'Dyrynda\Database\Support\Casts\EfficientUuid';
        }

        // Handle object instances (Laravel 12+)
        return $castType instanceof Casts\EfficientUuid;
    }
}
