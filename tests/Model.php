<?php

namespace Tests;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use GeneratesUuid;

    protected $table = 'posts';

    protected $guarded = [];

    public $timestamps = false;
}
