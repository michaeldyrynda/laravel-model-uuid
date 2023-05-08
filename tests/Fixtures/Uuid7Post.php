<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid7Post extends Model
{
    use GeneratesUuid;

    public function uuidVersion(): string
    {
        return 'uuid7';
    }
}
