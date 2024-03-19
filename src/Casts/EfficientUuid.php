<?php

namespace Dyrynda\Database\Support\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Ramsey\Uuid\Uuid;

class EfficientUuid implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (blank($value)) {
            return;
        }

        return Uuid::fromBytes($value)->toString();
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed  $value
     * @return array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (blank($value)) {
            return;
        }

        return [
            $key => Uuid::fromString(strtolower($value))->getBytes(),
        ];
    }
}
