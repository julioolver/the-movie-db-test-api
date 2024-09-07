<?php

namespace App\Domain\UseCases\Movies;

use App\Domain\Repositories\MovieApiRepositoryInterface;
use App\Infrastructure\Cache\MovieCacheService;
use App\Infrastructure\Persistence\Movie\MovieEloquentRepository;
use Illuminate\Support\Facades\Auth;

class GetMoviesUseCase
{

    public function __construct(
        protected MovieApiRepositoryInterface $movieProvider,
        protected MovieEloquentRepository $movieRepository,
        protected MovieCacheService $movieCacheService
    ) {}

    public function execute(array $data): array
    {
        $user = Auth::user();

        $persistedMovies = $this->movieCacheService->getUserMoviesFromCache($user->id, fn() => $this->movieRepository->getPersistedMoviesForUser($user));

        $persistedMoviesMap = [];
        foreach ($persistedMovies as $movie) {
            $persistedMoviesMap[$movie->external_id] = [
                'watched' => $movie->pivot->watched,
                'favorite' => $movie->pivot->favorite,
                'watch_later' => $movie->pivot->watch_later,
            ];
        }

        $movies = $this->movieProvider->searchMovies($data['query'], $data['page'], $persistedMoviesMap);

        return $movies;
    }
}
