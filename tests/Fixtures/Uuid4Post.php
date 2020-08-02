<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class Uuid4Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid4';

    /**
     * {@inheritdoc}
     */
    protected $casts = ['uuid' => EfficientUuid::class];
}
