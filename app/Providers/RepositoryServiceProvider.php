<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $models =[
            ,
            'Client',
        ];

        foreach ($models as $model) {
            $this->app->bind('App\\Interfaces\\' . $model . 'Interface', 'App\\Repositories\\' . $model . 'Repository');
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
