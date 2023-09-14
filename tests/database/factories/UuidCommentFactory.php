<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Fixtures\Uuid\Comment;
use Tests\Fixtures\Uuid\Post;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'post_id' => factory(Post::class),
        'body' => $faker->sentence,
    ];
});
