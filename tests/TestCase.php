<?php

namespace Tests;

use Dyrynda\Database\LaravelEfficientUuidServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
            LaravelEfficientUuidServiceProvider::class,
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
            $table->string('title');
        });
    }
}
