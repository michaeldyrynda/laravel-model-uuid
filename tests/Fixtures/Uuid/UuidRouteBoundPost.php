<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\BindsOnUuid;

class UuidRouteBoundPost extends Model
{
    use BindsOnUuid;

    public function uuidColumn(): string
    {
        return 'uuid';
    }
}
