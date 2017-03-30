<?php

namespace Tests;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use PHPUnit_Framework_TestCase;
use Tests\CustomUncastPost;

class UuidTest extends PHPUnit_Framework_TestCase
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
            $table->uuid('customField')->nullable()->default(null);
            $table->string('title');
        });
    }

    /** @test */
    public function it_sets_the_uuid_when_creating_a_new_model()
    {
        $post = Post::create(['title' => 'Test post']);

        $this->assertNotNull($post);
    }

    /** @test */
    public function it_does_not_override_the_uuid_if_it_is_already_set()
    {
        $post = Post::create(['title' => 'Test post', 'uuid' => '24f6c768-6276-4f34-bfa1-e7c8ba9514ea']);

        $this->assertEquals('24f6c768-6276-4f34-bfa1-e7c8ba9514ea', $post->uuid);
    }

    /** @test */
    public function you_can_set_a_custom_uuid_field_name()
    {
        $post = CustomPost::create(['title' => 'Test post']);

        $this->assertNull($post->uuid);
        $this->assertNotNull($post->customField);
    }

    /** @test */
    public function you_can_find_a_model_by_its_uuid()
    {
        Post::create(['title' => 'test post', 'uuid' => '55635d83-10bc-424f-bf3f-395ea7a5b47f']);

        $post = Post::whereUuid('55635d83-10bc-424f-bf3f-395ea7a5b47F')->first();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('55635d83-10bc-424f-bf3f-395ea7a5b47f', $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_its_uuid_when_using_a_custom_uuid_field_name()
    {
        $post = CustomPost::create(['title' => 'test post', 'customField' => 'bf3557c2-d848-4915-8457-d5873c171a40']);

        $post = CustomPost::whereUuid('bf3557c2-d848-4915-8457-d5873c171a40')->first();

        $this->assertInstanceOf(CustomPost::class, $post);
        $this->assertEquals('bf3557c2-d848-4915-8457-d5873c171a40', $post->customField);
    }

    /** @test */
    public function you_can_generate_a_uuid_without_casting()
    {
        $post = UncastPost::create(['title' => 'test post']);

        $this->assertNotNull($post->uuid);
    }

    /** @test */
    public function you_can_specify_a_uuid_without_casting()
    {
        $post = UncastPost::create(['title' => 'test-post', 'uuid' => 'aa9832e0-5fea-492c-8fe2-6f2d1e209209']);

        $this->assertEquals('aa9832e0-5fea-492c-8fe2-6f2d1e209209', $post->uuid);
    }

    /** @test */
    public function you_can_find_a_model_by_uuid_without_casting()
    {
        UncastPost::create(['title' => 'test-post', 'uuid' => 'b270f651-4db8-407b-aade-8666aca2750e']);

        $post = UncastPost::whereUuid('b270f651-4db8-407b-aade-8666aca2750e')->first();

        $this->assertInstanceOf(UncastPost::class, $post);
        $this->assertEquals('b270f651-4db8-407b-aade-8666aca2750e', $post->uuid);
    }

    /** @test */
    public function you_can_specify_a_custom_uuid_without_casting()
    {
        $post = CustomUncastPost::create(['title' => 'test post', 'customField' => '55af0ad1-f0ba-4a8f-b6e4-c6370ffd4c1e']);

        $this->assertNull($post->uuid);
        $this->assertEquals('55af0ad1-f0ba-4a8f-b6e4-c6370ffd4c1e', $post->customField);
    }

    /** @test */
    public function you_can_find_a_model_with_a_custom_field_without_casting()
    {
        CustomUncastPost::create(['title' => 'test post', 'customField' => '890b2200-9ee1-43f9-821f-445923ee1b87']);

        $post = CustomUncastPost::whereUuid('890b2200-9ee1-43f9-821f-445923ee1b87')->first();

        $this->assertInstanceOf(CustomUncastPost::class, $post);
        $this->assertEquals('890b2200-9ee1-43f9-821f-445923ee1b87', $post->customField);
    }
}
