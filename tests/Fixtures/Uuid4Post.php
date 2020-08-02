<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\GeneratesUuid;

class Uuid4Post extends Model
{
    use GeneratesUuid;

    protected $uuidVersion = 'uuid4';
}
