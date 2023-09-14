<?php

namespace Tests\Fixtures\Ulid;

class CustomUlidPost extends Model
{
    public function ulidColumn(): string
    {
        return 'custom_ulid';
    }
}
