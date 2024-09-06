<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Movie;

interface MovieRepositoryInterface
{
    public function findById(int $id): ?Movie;
    public function findByTitle(string $title): ?Movie;
    public function create(Movie $movie): Movie;
}
