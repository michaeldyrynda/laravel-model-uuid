<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Tests\Fixtures\Uuid\CustomCastUuidPost;
use Tests\Fixtures\Uuid\CustomUuidPost;
use Tests\Fixtures\Uuid\CustomUuidRouteBoundPost;
use Tests\Fixtures\Uuid\EfficientUuidPost;
use Tests\Fixtures\Uuid\MultipleUuidPost;
use Tests\Fixtures\Uuid\MultipleUuidRouteBoundPost;
use Tests\Fixtures\Uuid\OrderedPost;
use Tests\Fixtures\Uuid\Post;
use Tests\Fixtures\Uuid\UuidRouteBoundPost;

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
