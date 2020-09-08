<?php

namespace Tests\Fixtures;

class MultipleUuidPost extends Model
{
    public function uuidColumns(): array
    {
        return ['uuid', 'custom_uuid'];
    }
}
