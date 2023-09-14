<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

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
