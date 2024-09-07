<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Movie;
use App\Domain\Entities\User;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?Movie;
    public function findByExternalId(string $externalId): ?Movie;
    public function findByTitle(string $title): ?Movie;
    public function create(Movie $movie): Movie;
    public function createUserMovieStatus(User $user, Movie $movie, array $status): void;
}
