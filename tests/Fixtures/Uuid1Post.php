<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid1Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid1';
}
