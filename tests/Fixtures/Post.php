<?php

namespace Tests\Fixtures;

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
