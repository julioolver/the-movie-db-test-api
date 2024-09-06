<?php

namespace App\Domain\Repositories;

interface MovieApiRepositoryInterface
{
    public function searchMovies(string $query, int $page): array;
    public function respondMovies(array $movies): array;
}
