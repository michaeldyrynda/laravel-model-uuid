<?php

namespace Tests\Fixtures\Uuid;

class Comment extends Model
{
    protected $table = 'uuid_comments';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
