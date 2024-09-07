<?php

namespace App\Domain\UseCases\Movies;

use App\Domain\Repositories\MovieApiRepositoryInterface;
use App\Infrastructure\Persistence\Movie\MovieEloquentRepository;

class GetMoviesUseCase
{

    public function __construct(protected MovieApiRepositoryInterface $movieProvider, protected MovieEloquentRepository $movieRepository) {}

    public function execute(array $data): array
    {
        $persistedMovies = $this->movieRepository->getPersistedMoviesForUser();

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
