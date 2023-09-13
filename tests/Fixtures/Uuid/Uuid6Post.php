<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid6Post extends Model
{
    use GeneratesUuid;

    public function uuidVersion(): string
    {
        return 'uuid6';
    }
}
