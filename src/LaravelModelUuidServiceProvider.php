<?php

namespace Dyrynda\Database\Support;

use Illuminate\Support\ServiceProvider;

class LaravelModelUuidServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Load package configuration file
        $configPath = __DIR__ . '/../config/model-uuid.php';
        $this->mergeConfigFrom($configPath, 'model-uuid');

        // Add ability to publish the configuration file
        $this->publishes([$configPath => config_path('model-uuid.php')], 'config');
    }
}
