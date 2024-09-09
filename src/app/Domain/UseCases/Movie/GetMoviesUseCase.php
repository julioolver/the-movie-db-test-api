<?php

namespace App\Domain\UseCases\Movie;

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
            $persistedMoviesMap[$movie['external_id']] = [
                'watched' => $movie['watched'],
                'favorite' => $movie['favorite'],
                'watch_later' => $movie['watch_later'],
                'internal_id' => $movie['id'],
                'in_db' => true
            ];
        }

        $movies = $this->movieProvider->searchMovies($data['query'], $data['page'], $persistedMoviesMap);

        return $movies;
    }
}
