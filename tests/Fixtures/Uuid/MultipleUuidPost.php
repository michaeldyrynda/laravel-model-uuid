<?php

namespace Tests\Fixtures\Uuid;

class MultipleUuidPost extends Model
{
    public function uuidColumns(): array
    {
        return ['uuid', 'custom_uuid'];
    }
}
