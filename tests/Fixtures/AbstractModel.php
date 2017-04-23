<?php

namespace Tests\Fixtures;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class AbstractModel extends BaseModel
{
    use GeneratesUuid;

    /**
     * {@inheritdoc}
     */
    protected $table = 'posts';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;
}
