<?php

namespace Tests;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use PHPUnit_Framework_TestCase;
use Tests\CustomPost;
use Tests\Post;

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
        $post = Post::create(['title' => 'Test post', 'uuid' => 'uuid-already-set']);

        $this->assertEquals('uuid-already-set', $post->uuid);
    }

    /** @test */
    public function you_can_set_a_custom_uuid_field_name()
    {
        $post = CustomPost::create(['title' => 'Test post']);

        $this->assertNull($post->uuid);
        $this->assertNotNull($post->customField);
    }
}
