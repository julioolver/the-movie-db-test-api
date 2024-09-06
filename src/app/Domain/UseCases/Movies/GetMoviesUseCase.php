<?php

namespace App\Domain\UseCases\Movies;

use App\Domain\Repositories\MovieApiRepositoryInterface;

class GetMoviesUseCase
{

    public function __construct(protected MovieApiRepositoryInterface $movieProvider) {}

    public function execute(array $data): array
    {
        $movies = $this->movieProvider->searchMovies($data['query'], $data['page']);

        return $movies;
    }
}
