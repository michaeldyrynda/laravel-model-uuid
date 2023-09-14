<?php

namespace Tests\Fixtures\Ulid;

class MultipleUlidPost extends Model
{
    public function ulidColumns(): array
    {
        return ['ulid', 'custom_ulid'];
    }
}
