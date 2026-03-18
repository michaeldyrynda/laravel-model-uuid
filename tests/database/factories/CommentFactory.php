<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Tests\Fixtures\Comment;
use Tests\Fixtures\Post;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'post_id' => factory(Post::class),
        'body' => $faker->sentence,
    ];
});
