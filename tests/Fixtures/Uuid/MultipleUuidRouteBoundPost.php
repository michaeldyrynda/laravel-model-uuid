<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\BindsOnUuid;

class MultipleUuidRouteBoundPost extends Model
{
    use BindsOnUuid;

    public function uuidColumns(): array
    {
        return [
            'uuid', 'custom_uuid',
        ];
    }
}
