<?php

namespace Tests\Fixtures\Uuid;

class OrderedPost extends Model
{
    public function uuidVersion(): string
    {
        return 'ordered';
    }
}
