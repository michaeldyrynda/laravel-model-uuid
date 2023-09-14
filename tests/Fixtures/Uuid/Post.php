<?php

namespace Tests\Fixtures\Uuid;

class Post extends Model
{
    protected $table = 'uuid_posts';

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
