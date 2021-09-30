<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\BindsOnUuid;

class CustomUuidRouteBoundPost extends Model
{
    use BindsOnUuid;

    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
