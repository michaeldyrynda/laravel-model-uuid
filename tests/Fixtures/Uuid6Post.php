<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class Uuid6Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid6';

    /**
     * {@inheritdoc}
     */
    protected $casts = ['uuid' => EfficientUuid::class];
}
