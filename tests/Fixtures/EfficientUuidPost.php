<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Support\Casts\EfficientUuid;

class EfficientUuidPost extends Model
{
    use GeneratesUuid;

    /**
     * {@inheritdoc}
     */
    protected $casts = ['efficient_uuid' => EfficientUuid::class];

    public function uuidColumn(): string
    {
        return 'efficient_uuid';
    }
}
