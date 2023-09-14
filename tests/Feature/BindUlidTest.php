<?php

namespace Tests\Feature;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Tests\Fixtures\Ulid\CustomUlidRouteBoundPost;
use Tests\Fixtures\Ulid\MultipleUlidRouteBoundPost;
use Tests\Fixtures\Ulid\UlidRouteBoundPost;
use Tests\TestCase;

class BindUlidTest extends TestCase
{
    /** @test */
    public function it_binds_to_default_ulid_field()
    {
        $post = factory(UlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (UlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->ulid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_default_ulid_field_value()
    {
        $post = factory(UlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (UlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_ulid)->assertNotFound();
        $this->get(route('posts.show', $post->custom_ulid))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_custom_ulid_field()
    {
        $post = factory(CustomUlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomUlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_ulid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_custom_ulid_field_value()
    {
        $post = factory(CustomUlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post}', function (CustomUlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->ulid)->assertNotFound();
        $this->get(route('posts.show', $post->ulid))->assertNotFound();
    }

    /** @test */
    public function it_binds_to_declared_ulid_column_instead_of_default_when_custom_key_used()
    {
        $post = factory(MultipleUlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_ulid}', function (MultipleUlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->custom_ulid)->assertSuccessful();
        $this->get(route('posts.show', $post))->assertSuccessful();
    }

    /** @test */
    public function it_fails_on_invalid_ulid_when_custom_route_key_used()
    {
        $post = factory(MultipleUlidRouteBoundPost::class)->create();

        Route::middleware(SubstituteBindings::class)->get('/posts/{post:custom_ulid}', function (MultipleUlidRouteBoundPost $post) {
            return $post;
        })->name('posts.show');

        $this->get('/posts/'.$post->ulid)->assertNotFound();
        $this->get(route('posts.show', $post->ulid))->assertNotFound();
    }
}
