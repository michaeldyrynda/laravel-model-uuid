<?php

namespace Tests\Fixtures;

class Comment extends Model
{
    protected $table = 'comments';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
