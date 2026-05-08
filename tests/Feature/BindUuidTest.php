<?php

declare(strict_types=1);

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Tests\Fixtures\CustomUuidRouteBoundPost;
use Tests\Fixtures\MultipleUuidRouteBoundPost;
use Tests\Fixtures\UuidRouteBoundPost;

use function Pest\Laravel\get;

it('binds to default uuid field', function () {
    $post = factory(UuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (UuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->uuid}")->assertSuccessful();
    get(route('posts.show', $post))->assertSuccessful();
});

it('fails on invalid default uuid field value', function () {
    $post = factory(UuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (UuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->custom_uuid}")->assertNotFound();
    get(route('posts.show', $post->custom_uuid))->assertNotFound();
});

it('binds to custom uuid field', function () {
    $post = factory(CustomUuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomUuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->custom_uuid}")->assertSuccessful();
    get(route('posts.show', $post))->assertSuccessful();
});

it('fails on invalid custom uuid field value', function () {
    $post = factory(CustomUuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomUuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->uuid}")->assertNotFound();
    get(route('posts.show', $post->uuid))->assertNotFound();
});

it('binds to declared uuid column instead of default when custom key used', function () {
    $post = factory(MultipleUuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_uuid}', function (MultipleUuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->custom_uuid}")->assertSuccessful();
    get(route('posts.show', $post))->assertSuccessful();
});

it('fails on invalid uuid when custom route key used', function () {
    $post = factory(MultipleUuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_uuid}', function (MultipleUuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->uuid}")->assertNotFound();
    get(route('posts.show', $post->uuid))->assertNotFound();
});

it('bypasses uuid binding when explicit non uuid route key is used', function () {
    $post = factory(MultipleUuidRouteBoundPost::class)->create();

    Route::middleware(SubstituteBindings::class)->get('/posts/{post:id}', function (MultipleUuidRouteBoundPost $post) {
        return $post;
    })->name('posts.show');

    get("/posts/{$post->id}")->assertSuccessful();
    get(route('posts.show', $post->id))->assertSuccessful();
});
