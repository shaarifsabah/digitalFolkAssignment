<?php

namespace App\Providers;

use App\Repositories\Contracts\TranslationRepositoryInterface;
use App\Repositories\Eloquent\TranslationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            TranslationRepositoryInterface::class,
            TranslationRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
