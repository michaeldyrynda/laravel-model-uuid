<?php

namespace Tests\Fixtures\Ulid;

use Dyrynda\Database\Support\BindsOnUlid;

class UlidRouteBoundPost extends Model
{
    use BindsOnUlid;

    public function uuidColumn(): string
    {
        return 'uuid';
    }
}
