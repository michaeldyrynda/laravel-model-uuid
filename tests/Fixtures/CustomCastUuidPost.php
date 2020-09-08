<?php

namespace Tests\Fixtures;

class CustomCastUuidPost extends Model
{
    public function uuidColumn(): string
    {
        return 'custom_uuid';
    }
}
