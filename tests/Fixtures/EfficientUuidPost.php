<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class EfficientUuidPost extends Model
{
    use GeneratesUuid;

    protected $table = 'posts';

    public $timestamps = false;

    protected $casts = [
        'uuid' => EfficientUuid::class,
        'custom_uuid' => EfficientUuid::class,
    ];

    public function uuidColumn(): string
    {
        return 'uuid';
    }

    public function uuidColumns(): array
    {
        return ['uuid', 'custom_uuid'];
    }
}
