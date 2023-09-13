<?php

namespace Tests\Fixtures\Uuid;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use GeneratesUuid;

    /**
     * {@inheritdoc}
     */
    protected $table = 'uuid_posts';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
