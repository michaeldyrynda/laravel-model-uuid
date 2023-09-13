<?php

namespace Tests\Fixtures\Ulid;

class Post extends Model
{
    protected $table = 'ulid_posts';

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
