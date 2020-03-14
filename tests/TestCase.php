<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Dyrynda\Database\LaravelEfficientUuidServiceProvider;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__.'/database/migrations'));

        $this->withFactories(realpath(__DIR__.'/database/factories'));
    }

    public function getPackageProviders($app)
    {
        return [
            LaravelEfficientUuidServiceProvider::class,
        ];
    }
}
