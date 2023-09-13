<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Fixtures\Ulid\Comment;
use Tests\Fixtures\Ulid\CustomCastUlidPost;
use Tests\Fixtures\Ulid\CustomUlidPost;
use Tests\Fixtures\Ulid\MultipleUlidPost;
use Tests\Fixtures\Ulid\OrderedPost;
use Tests\Fixtures\Ulid\Post;
use Tests\Fixtures\Ulid\UncastPost;
use Tests\TestCase;

class UlidTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_sets_the_ulid_when_creating_a_new_model()
    {
        $post = Post::create(['title' => 'Test post']);

        $this->assertNotNull($post->ulid);
    }

    /** @test */
    public function it_does_not_override_the_ulid_if_it_is_already_set()
    {
        $ulid = '01ha3jhmpz4mgvgf772wyksj9d';

        $post = Post::create(['title' => 'Test post', 'ulid' => $ulid]);

        $this->assertSame($ulid, $post->ulid);
    }

    /** @test */
    public function you_can_find_a_model_by_its_ulid()
    {
        $ulid = '01ha3jhmpzyhympbf0d7q34fg8';

        Post::create(['title' => 'test post', 'ulid' => $ulid]);

        $post = Post::whereUlid($ulid)->first();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame($ulid, $post->ulid);
    }

    /** @test */
    public function you_can_find_a_model_by_custom_ulid_parameter()
    {
        $ulid = '01ha3jhmpztcfkbpc6he6zcfs7';
        $custom_ulid = '01ha3jhmpzs0fjja2v79cjng4j';

        MultipleUlidPost::create(['title' => 'test post', 'ulid' => $ulid, 'custom_ulid' => $custom_ulid]);

        $post1 = MultipleUlidPost::whereUlid($ulid)->first();
        $this->assertInstanceOf(MultipleUlidPost::class, $post1);
        $this->assertSame($ulid, $post1->ulid);

        $post2 = MultipleUlidPost::whereUlid($ulid, 'ulid')->first();
        $this->assertInstanceOf(MultipleUlidPost::class, $post2);
        $this->assertSame($ulid, $post2->ulid);

        $post3 = MultipleUlidPost::whereUlid($custom_ulid, 'custom_ulid')->first();
        $this->assertInstanceOf(MultipleUlidPost::class, $post3);
        $this->assertSame($custom_ulid, $post3->custom_ulid);
    }

    /** @test */
    public function you_can_search_by_array_of_ulids()
    {
        $first = Post::create(['title' => 'first post', 'ulid' => '01ha3jhmpzhpf2hvycgrh1r2k7']);
        $second = Post::create(['title' => 'second post', 'ulid' => '01ha3jhmpze5c2t5crycf0z1c5']);

        $this->assertEquals(2, Post::whereUlid([
            '01ha3jhmpzhpf2hvycgrh1r2k7',
            '01ha3jhmpze5c2t5crycf0z1c5',
        ])->count());
    }

    /** @test */
    public function you_can_search_by_array_of_ulids_for_custom_column()
    {
        $first = CustomCastUlidPost::create(['title' => 'first post', 'custom_ulid' => '01ha3jhmpzp9mnxrtsbq6kwcmq']);
        $second = CustomCastUlidPost::create(['title' => 'second post', 'custom_ulid' => '01ha3jhmpzvyb4e2ht3y25hvxw']);

        $this->assertEquals(2, CustomCastUlidPost::whereUlid([
            '01ha3jhmpzp9mnxrtsbq6kwcmq',
            '01ha3jhmpzvyb4e2ht3y25hvxw',
        ], 'custom_ulid')->count());
    }

    /** @test */
    public function you_can_generate_a_ulid_without_casting()
    {
        $post = UncastPost::create(['title' => 'test post']);

        $this->assertNotNull($post->ulid);
    }

    /** @test */
    public function you_can_generate_a_ulid_with_casting_and_a_custom_field_name()
    {
        $post = CustomCastUlidPost::create(['title' => 'test post']);

        $this->assertNotNull($post->custom_ulid);
    }

    /** @test */
    public function you_can_specify_a_ulid_without_casting()
    {
        $ulid = '01ha3jhmpz90kbrmf088p7sfpb';

        $post = UncastPost::create(['title' => 'test-post', 'ulid' => $ulid]);

        $this->assertSame($ulid, $post->ulid);
    }

    /** @test */
    public function you_can_find_a_model_by_ulid_without_casting()
    {
        $ulid = '01ha3jhmpz42w72r19r4j740d1';

        UncastPost::create(['title' => 'test-post', 'ulid' => $ulid]);

        $post = UncastPost::whereUlid($ulid)->first();

        $this->assertInstanceOf(UncastPost::class, $post);
        $this->assertSame($ulid, $post->ulid);
    }

    /** @test */
    public function it_allows_configurable_ulid_column_names()
    {
        $post = CustomUlidPost::create(['title' => 'test-post']);

        $this->assertNotNull($post->custom_ulid);
    }

    /** @test */
    public function it_handles_an_invalid_ulid()
    {
        $ulid = 'b270f651-4db8-407b-aade-8666aca2750e';

        Post::create(['title' => 'invalid ulid', 'ulid' => $ulid]);

        $this->expectException(ModelNotFoundException::class);

        Post::whereUlid('invalid ulid')->firstOrFail();
    }

    /** @test */
    public function it_handles_a_null_ulid_column()
    {
        tap(Model::withoutEvents(function () {
            return Post::create([
                'title' => 'Nullable ulid',
                'ulid' => null,
            ]);
        }), function ($post) {
            $this->assertNull($post->ulid);
        });
    }

    /** @test */
    public function it_handles_queries_with_multiple_ulid_columns()
    {
        $post = factory(Post::class)->create([
            'ulid' => '01ha3jhmpzt4yemas4bxb4c4b4',
        ]);
        $comment = $post->comments()->save(factory(Comment::class)->make([
            'ulid' => '01ha3jhmpz95rcvhj6jrbb7qvd',
        ]));

        tap($post->comments()->whereUlid($comment->ulid)->first(), function ($comment) {
            $this->assertNotNull($comment);
            $this->assertEquals('01ha3jhmpzt4yemas4bxb4c4b4', $comment->post->ulid);
        });
    }
}
