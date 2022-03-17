<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class PrimaryEfficientUuidPost extends Model
{
    use GeneratesUuid;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'id' => EfficientUuid::class,
        'efficient_uuid' => EfficientUuid::class,
    ];

    public $table = 'efficient_posts';

    public $incrementing = false;

    public function uuidColumns(): array
    {
        return ['id', 'efficient_uuid'];
    }
}
