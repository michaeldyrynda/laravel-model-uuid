<?php

namespace Tests\Fixtures\Ulid;

class CustomCastUlidPost extends Model
{
    public function ulidColumn(): string
    {
        return 'custom_ulid';
    }
}
