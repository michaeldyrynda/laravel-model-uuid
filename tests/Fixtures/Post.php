<?php

namespace Tests\Fixtures;

use Tests\Fixtures\Model;

class Post extends Model
{
    protected $casts = ['uuid' => 'uuid'];
}
