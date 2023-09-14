<?php

namespace Tests\Fixtures\Ulid;

use Dyrynda\Database\Support\BindsOnUlid;

class CustomUlidRouteBoundPost extends Model
{
    use BindsOnUlid;

    public function ulidColumn(): string
    {
        return 'custom_ulid';
    }
}
