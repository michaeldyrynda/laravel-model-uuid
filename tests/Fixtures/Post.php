<?php

namespace Tests\Fixtures;

class Post extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected $casts = ['uuid' => 'uuid'];
}
