<?php

namespace Tests\Fixtures;

class CustomUuidPost extends Model
{
    public function uuidColumn()
    {
        return 'custom_uuid';
    }
}
