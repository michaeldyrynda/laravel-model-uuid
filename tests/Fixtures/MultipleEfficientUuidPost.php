<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class MultipleEfficientUuidPost extends Model
{
    use GeneratesUuid;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'efficient_uuid' => EfficientUuid::class,
        'another_efficient_uuid' => EfficientUuid::class,
    ];

    public function uuidColumns(): array
    {
        return ['efficient_uuid', 'another_efficient_uuid'];
    }
}
