<?php

namespace Tests\Fixtures\Ulid;

class Comment extends Model
{
    protected $table = 'ulid_comments';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
