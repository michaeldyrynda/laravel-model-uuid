<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid4Post extends Model
{
    use GeneratesUuid;

    public function uuidVersion(): string
    {
        return 'uuid4';
    }
}
