<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\Fixtures\Comment;
use Tests\Fixtures\CustomCastUuidPost;
use Tests\Fixtures\CustomUuidPost;
use Tests\Fixtures\EfficientUuidPost;
use Tests\Fixtures\MultipleUuidPost;
use Tests\Fixtures\OrderedPost;
use Tests\Fixtures\Post;
use Tests\Fixtures\UncastPost;
use Tests\Fixtures\Uuid1Post;
use Tests\Fixtures\Uuid4Post;
use Tests\Fixtures\Uuid6Post;
use Tests\Fixtures\Uuid7Post;
use Tests\TestCase;

class UuidTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_sets_the_uuid_when_creating_a_new_model()
    {
        $post = Post::create(['title' => 'Test post']);

        $this->assertNotNull($post->uuid);
    }

    /** @test */
    public function it_does_not_override_the_uuid_if_it_is_already_set()
    {
        $uuid = '24f6c768-6276-4f34-bfa1-e7c8ba9514ea';

        $post = Post::create(['title' => 'Test post', 'uuid' => $uuid]);

        $this->assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_its_uuid()
    {
        $uuid = '55635d83-10bc-424f-bf3f-395ea7a5b47f';

        Post::create(['title' => 'test post', 'uuid' => $uuid]);

        $post = Post::whereUuid($uuid)->first();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_custom_uuid_parameter()
    {
        $uuid = '6499332d-25e1-4d75-bd92-c6ded0820fb3';
        $custom_uuid = '99635d83-05bc-424f-bf3f-395ea7a5b323';

        MultipleUuidPost::create(['title' => 'test post', 'uuid' => $uuid, 'custom_uuid' => $custom_uuid]);

        $post1 = MultipleUuidPost::whereUuid($uuid)->first();
        $this->assertInstanceOf(MultipleUuidPost::class, $post1);
        $this->assertSame($uuid, $post1->uuid);

        $post2 = MultipleUuidPost::whereUuid($uuid, 'uuid')->first();
        $this->assertInstanceOf(MultipleUuidPost::class, $post2);
        $this->assertSame($uuid, $post2->uuid);

        $post3 = MultipleUuidPost::whereUuid($custom_uuid, 'custom_uuid')->first();
        $this->assertInstanceOf(MultipleUuidPost::class, $post3);
        $this->assertSame($custom_uuid, $post3->custom_uuid);
    }

    /** @test */
    public function you_can_search_by_array_of_uuids()
    {
        $first = Post::create(['title' => 'first post', 'uuid' => '8ab48e77-d9cd-4fe7-ace5-a5a428590c18']);
        $second = Post::create(['title' => 'second post', 'uuid' => 'c7c26456-ddb0-45cd-9b1c-318296cce7a3']);

        $this->assertEquals(2, Post::whereUuid([
            '8ab48e77-d9cd-4fe7-ace5-A5A428590C18',
            'c7c26456-ddb0-45cd-9b1c-318296cce7a3',
        ])->count());
    }

    /** @test */
    public function you_can_search_by_array_of_efficient_uuids()
    {
        $first = EfficientUuidPost::create(['title' => 'first post', 'efficient_uuid' => '8ab48e77-d9cd-4fe7-ace5-a5a428590c18']);
        $second = EfficientUuidPost::create(['title' => 'second post', 'efficient_uuid' => 'c7c26456-ddb0-45cd-9b1c-318296cce7a3']);

        $this->assertEquals(2, EfficientUuidPost::whereUuid([
            '8ab48e77-d9cd-4fe7-ace5-A5A428590C18',
            'c7c26456-ddb0-45cd-9b1c-318296cce7a3',
        ])->count());
    }

    /** @test */
    public function you_can_search_by_array_of_uuids_for_custom_column()
    {
        $first = CustomCastUuidPost::create(['title' => 'first post', 'custom_uuid' => '8ab48e77-d9cd-4fe7-ace5-a5a428590c18']);
        $second = CustomCastUuidPost::create(['title' => 'second post', 'custom_uuid' => 'c7c26456-ddb0-45cd-9b1c-318296cce7a3']);

        $this->assertEquals(2, CustomCastUuidPost::whereUuid([
            '8ab48e77-d9cd-4fe7-ace5-A5A428590C18',
            'c7c26456-ddb0-45cd-9b1c-318296cce7a3',
        ], 'custom_uuid')->count());
    }

    /** @test */
    public function you_can_search_by_array_of_uuids_which_contains_an_invalid_uuid()
    {
        $first = EfficientUuidPost::create(['title' => 'first post', 'uuid' => '8ab48e77-d9cd-4fe7-ace5-a5a428590c18']);
        $second = EfficientUuidPost::create(['title' => 'second post', 'uuid' => 'c7c26456-ddb0-45cd-9b1c-318296cce7a3']);

        $this->assertEquals(2, Post::whereUuid([
            '8ab48e77-d9cd-4fe7-ace5-A5A428590C18',
            'c7c26456-ddb0-45cd-9b1c-318296cce7a3',
            'this is invalid',
        ])->count());
    }

    /** @test */
    public function you_can_generate_a_uuid_without_casting()
    {
        $post = UncastPost::create(['title' => 'test post']);

        $this->assertNotNull($post->uuid);
    }

    /** @test */
    public function you_can_generate_a_uuid_with_casting_and_a_custom_field_name()
    {
        $post = CustomCastUuidPost::create(['title' => 'test post']);

        $this->assertNotNull($post->custom_uuid);
    }

    /** @test */
    public function you_can_specify_a_uuid_without_casting()
    {
        $uuid = 'aa9832e0-5fea-492c-8fe2-6f2d1e209209';

        $post = UncastPost::create(['title' => 'test-post', 'uuid' => $uuid]);

        $this->assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_uuid_without_casting()
    {
        $uuid = 'b270f651-4db8-407b-aade-8666aca2750e';

        UncastPost::create(['title' => 'test-post', 'uuid' => $uuid]);

        $post = UncastPost::whereUuid($uuid)->first();

        $this->assertInstanceOf(UncastPost::class, $post);
        $this->assertSame($uuid, $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_uuid_with_casting()
    {
        $uuid = 'b270f651-4db8-407b-aade-8666aca2750e';

        EfficientUuidPost::create(['title' => 'efficient uuid', 'efficient_uuid' => $uuid]);

        $post = EfficientUuidPost::whereUuid($uuid)->first();

        $this->assertInstanceOf(EfficientUuidPost::class, $post);
        $this->assertSame($uuid, $post->efficient_uuid);
    }

    /** @test */
    public function it_handles_time_ordered_uuids()
    {
        $post = OrderedPost::create(['title' => 'test-post']);

        $this->assertInstanceOf(OrderedPost::class, $post);
        $this->assertNotNull($post->uuid);
    }

    /** @test */
    public function it_allows_configurable_uuid_column_names()
    {
        $post = CustomUuidPost::create(['title' => 'test-post']);

        $this->assertNotNull($post->custom_uuid);
    }

    /**
     * @test
     *
     * @dataProvider factoriesWithUuidProvider
     */
    public function it_handles_working_with_various_uuid_casts($model, $column)
    {
        tap(factory($model)->create(), function ($post) use ($column) {
            $this->assertNotNull($post->{$column});
        });
    }

    /** @test */
    public function it_handles_setting_an_efficient_uuid()
    {
        tap(EfficientUuidPost::create([
            'title' => 'Efficient uuid post',
            'efficient_uuid' => $uuid = $this->faker->uuid,
        ]), function ($post) use ($uuid) {
            $this->assertEquals($uuid, $post->efficient_uuid);
            $this->assertSame(
                Uuid::fromString($uuid)->getBytes(),
                $post->getRawOriginal('efficient_uuid')
            );
        });
    }

    /** @test */
    public function it_handles_an_invalid_uuid()
    {
        $uuid = 'b270f651-4db8-407b-aade-8666aca2750e';

        EfficientUuidPost::create(['title' => 'efficient uuid', 'efficient_uuid' => $uuid]);

        $this->expectException(ModelNotFoundException::class);

        EfficientUuidPost::whereUuid('invalid uuid')->firstOrFail();
    }

    /** @test */
    public function it_handles_a_null_uuid_column()
    {
        tap(Model::withoutEvents(function () {
            return Post::create([
                'title' => 'Nullable uuid',
                'uuid' => null,
            ]);
        }), function ($post) {
            $this->assertNull($post->uuid);
        });
    }

    /** @test */
    public function it_handles_a_null_efficient_uuid_column()
    {
        tap(Model::withoutEvents(function () {
            return EfficientUuidPost::create([
                'title' => 'Nullable uuid',
                'efficient_uuid' => null,
            ]);
        }), function ($post) {
            $this->assertNull($post->efficient_uuid);
        });
    }

    /**
     * @test
     *
     * @dataProvider uuidVersionsProvider
     */
    public function it_handles_supported_uuid_versions($model, $version)
    {
        tap($model::create(['title' => 'test title']), function ($model) use ($version) {
            $this->assertEquals($version, Uuid::fromString($model->uuid)->getVersion());
        });
    }

    /** @test */
    public function it_handles_queries_with_multiple_uuid_columns()
    {
        $post = factory(Post::class)->create([
            'uuid' => '4e6c964d-4e9b-4023-be0e-f5a6529b7184',
        ]);
        $comment = $post->comments()->save(factory(Comment::class)->make([
            'uuid' => '8b8f4d17-e2b9-4f9d-9b1d-4e94ef5db644',
        ]));

        tap($post->comments()->whereUuid($comment->uuid)->first(), function ($comment) {
            $this->assertNotNull($comment);
            $this->assertEquals('4e6c964d-4e9b-4023-be0e-f5a6529b7184', $comment->post->uuid);
        });
    }

    public static function factoriesWithUuidProvider(): array
    {
        return [
            'regular uuid' => [Post::class, 'uuid'],
            'custom uuid' => [CustomUuidPost::class, 'custom_uuid'],
            'efficient uuid' => [EfficientUuidPost::class, 'efficient_uuid'],
        ];
    }

    public static function uuidVersionsProvider(): array
    {
        return [
            'uuid1' => [Uuid1Post::class, Uuid::UUID_TYPE_TIME],
            'uuid4' => [Uuid4Post::class, Uuid::UUID_TYPE_RANDOM],
            'uuid6' => [Uuid6Post::class, Uuid::UUID_TYPE_REORDERED_TIME],
            'uuid7' => [Uuid7Post::class, Uuid::UUID_TYPE_UNIX_TIME],
            'ordered' => [OrderedPost::class, Uuid::UUID_TYPE_REORDERED_TIME],
        ];
    }
}
