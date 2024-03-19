<?php

namespace Tests;

use Dyrynda\Database\Support\LaravelModelUuidServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(__DIR__.'/database/factories'));

        $this->setupDatabase($this->app);
    }

    public function getPackageProviders($app)
    {
        return [
            LaravelModelUuidServiceProvider::class,
        ];
    }

    protected function setupDatabase($app)
    {
        Schema::dropAllTables();

        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->nullable();
            $table->uuid('custom_uuid')->nullable();
            $table->efficientUuid('efficient_uuid')->nullable();
            $table->efficientUuid('custom_efficient_uuid')->nullable();
            $table->string('title');
        });

        $app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('post_id');
            $table->uuid('uuid')->nullable();
            $table->text('body');
        });
    }
}
