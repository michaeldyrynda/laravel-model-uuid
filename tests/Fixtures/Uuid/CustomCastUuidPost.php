<?php

namespace Tests\Fixtures\Uuid;

class CustomCastUuidPost extends Model
{
    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
