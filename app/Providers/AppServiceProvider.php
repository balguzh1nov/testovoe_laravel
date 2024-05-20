<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\NoteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NoteRepository::class, function ($app) {
            return new NoteRepository();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
