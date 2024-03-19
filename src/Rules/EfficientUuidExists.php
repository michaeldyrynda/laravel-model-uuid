<?php

namespace Dyrynda\Database\Support\Rules;

use Illuminate\Contracts\Validation\Rule;
use Ramsey\Uuid\Uuid;

class EfficientUuidExists implements Rule
{
    /** @var \Dyrynda\Database\Support\GeneratesUuid */
    protected $model;

    /** @var string */
    protected $column;

    public function __construct(string $model, string $column = 'uuid')
    {
        $this->model = new $model;

        $this->column = $column;
    }

    public function passes($attribute, $value): bool
    {
        if (Uuid::isValid($value ?: '')) {
            $binaryUuid = Uuid::fromString(strtolower($value))->getBytes();

            return $this->model->where($this->column, $binaryUuid)->exists();
        }

        return false;
    }

    public function message(): string
    {
        return trans('validation.exists');
    }
}
