<?php

namespace Tests\Fixtures;

class Post extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $casts = ['uuid' => 'uuid'];
}
