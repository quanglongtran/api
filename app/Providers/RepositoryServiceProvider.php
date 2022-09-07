<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Mail\MailRepositoryInterface::class,
            \App\Repositories\Mail\MailRepository::class,
        );

        $this->app->singleton(
            \App\Repositories\Notification\NotificationRepositoryInterface::class,
            \App\Repositories\Notification\NotificationRepository::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
