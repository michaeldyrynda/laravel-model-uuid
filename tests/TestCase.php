<?php

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
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
