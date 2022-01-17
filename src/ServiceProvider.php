<?php

namespace UserAdmin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/user-adminboard.php' => config_path('user-adminboard.php'),
            ], 'config');


            $this->commands([
                //
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/user-adminboard.php', 'user-adminboard');

        $this->app->bind('user-admin', function ($app) {
            return new UserAdminManager($app);
        });
    }
}
