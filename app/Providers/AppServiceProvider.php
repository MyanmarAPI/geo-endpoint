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
        //
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

}
