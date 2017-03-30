<?php

namespace Tests;

use Dyrynda\Database\Support\GeneratesUuid;
use Tests\Model;

class Post extends Model
{
    protected $casts = ['uuid' => 'uuid'];
}
