<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Fixtures\CustomCastUuidPost;
use Tests\Fixtures\CustomUuidPost;
use Tests\Fixtures\CustomUuidRouteBoundPost;
use Tests\Fixtures\EfficientUuidPost;
use Tests\Fixtures\MultipleUuidPost;
use Tests\Fixtures\MultipleUuidRouteBoundPost;
use Tests\Fixtures\OrderedPost;
use Tests\Fixtures\Post;
use Tests\Fixtures\UuidRouteBoundPost;

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

$factory->define(CustomUuidRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(UuidRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});

$factory->define(MultipleUuidRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'custom_uuid' => $faker->uuid,
        'title' => $faker->sentence,
    ];
});
