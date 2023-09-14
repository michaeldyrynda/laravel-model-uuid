<?php

namespace Tests\Fixtures\Ulid;

use Dyrynda\Database\Support\GeneratesUlid;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use GeneratesUlid;

    /**
     * {@inheritdoc}
     */
    protected $table = 'ulid_posts';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
