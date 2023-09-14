<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Symfony\Component\Uid\Ulid;
use Tests\Fixtures\Ulid\CustomCastUlidPost;
use Tests\Fixtures\Ulid\CustomUlidPost;
use Tests\Fixtures\Ulid\CustomUlidRouteBoundPost;
use Tests\Fixtures\Ulid\MultipleUlidPost;
use Tests\Fixtures\Ulid\MultipleUlidRouteBoundPost;
use Tests\Fixtures\Ulid\Post;
use Tests\Fixtures\Ulid\UlidRouteBoundPost;

$factory->define(CustomCastUlidPost::class, function (Faker $faker) {
    return [
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});

$factory->define(CustomUlidPost::class, function (Faker $faker) {
    return [
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});


$factory->define(MultipleUlidPost::class, function (Faker $faker) {
    return [
        'ulid' => Ulid::generate(),
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    return [
        'ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});

$factory->define(CustomUlidRouteBoundPost::class, function (Faker $faker) {
    return [
        'ulid' => Ulid::generate(),
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});

$factory->define(UlidRouteBoundPost::class, function (Faker $faker) {
    return [
        'ulid' => Ulid::generate(),
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});

$factory->define(MultipleUlidRouteBoundPost::class, function (Faker $faker) {
    return [
        'ulid' => Ulid::generate(),
        'custom_ulid' => Ulid::generate(),
        'title' => $faker->sentence,
    ];
});
