<?php

namespace Tests\Fixtures\Uuid;

class CustomUuidPost extends Model
{
    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
