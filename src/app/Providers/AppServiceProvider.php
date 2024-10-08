<?php

namespace App\Providers;

use App\Domain\Repositories\MovieApiRepositoryInterface;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Movie\MovieEloquentRepository;
use App\Infrastructure\Persistence\UserRepository;
use App\Infrastructure\Providers\TheMovieDbApiProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MovieApiRepositoryInterface::class, TheMovieDbApiProvider::class);
        $this->app->bind(MovieRepositoryInterface::class, MovieEloquentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
