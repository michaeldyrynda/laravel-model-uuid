<?php

namespace Tests\Feature;

use Tests\Fixtures\Post;
use Tests\Fixtures\UncastPost;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\OrderedPost;
use Illuminate\Events\Dispatcher;
use Tests\Fixtures\CustomUuidPost;
use Illuminate\Container\Container;
use Tests\Fixtures\MultipleUuidPost;
use Tests\Fixtures\CustomCastUuidPost;
use Illuminate\Database\Capsule\Manager;

class UuidTest extends TestCase
{
    public static function setupBeforeClass()
    {
        $manager = new Manager;
        $manager->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $manager->setEventDispatcher(new Dispatcher(new Container));
        $manager->setAsGlobal();
        $manager->bootEloquent();

        $manager->schema()->create('posts', function ($table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable()->default(null);
            $table->uuid('custom_uuid')->nullable()->default(null);
            $table->string('title');
        });
    }

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
}
