<?php

namespace App\Domain\Repositories;

interface MovieApiRepositoryInterface
{
    public function searchMovies(string $query, int $page, array $persistedMovies): array;

    public function providesFullMovieDetails(): bool;
    public function getMovieDetails(string $externalId): array;
    public function respondMovies(array $movies): array;
}
