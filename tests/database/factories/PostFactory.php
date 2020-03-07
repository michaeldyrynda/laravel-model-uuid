<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Tests\Fixtures\Post;
use Faker\Generator as Faker;
use Tests\Fixtures\OrderedPost;
use Tests\Fixtures\CustomUuidPost;
use Tests\Fixtures\MultipleUuidPost;
use Tests\Fixtures\EfficientUuidPost;
use Tests\Fixtures\CustomCastUuidPost;

$factory->define(CustomCastUuidPost::class, function (Faker $faker) {
    return [
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(CustomUuidPost::class, function (Faker $faker) {
    return [
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(EfficientUuidPost::class, function (Faker $faker) {
    return [
        'efficient_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(MultipleUuidPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(OrderedPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(UncastPostPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});
