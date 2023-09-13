<?php

namespace Tests\Fixtures\Ulid;

use Dyrynda\Database\Support\BindsOnUlid;

class MultipleUlidRouteBoundPost extends Model
{
    use BindsOnUlid;

    public function ulidColumns(): array
    {
        return [
            'ulid', 'custom_ulid',
        ];
    }
}
