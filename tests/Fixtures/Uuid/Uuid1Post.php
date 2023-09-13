<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid1Post extends Model
{
    use GeneratesUuid;

    public function uuidVersion(): string
    {
        return 'uuid1';
    }
}
