<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hexcores\MongoLite\Connection as MongoConnection;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $connection = MongoConnection::instance();

        $this->app->instance('connection', $connection);
    }

    /**
     * Register all artisan commands
     */
    protected function registerCommands()
    {
        $commands = [
            'App\Console\Commands\ImportCommand',
            'App\Console\Commands\JsonImportCommand'
        ];

        $this->commands($commands);
    }

}
