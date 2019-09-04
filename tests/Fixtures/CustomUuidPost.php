<?php

namespace Tests\Fixtures;

class CustomUuidPost extends Model
{
    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
