<?php

namespace App\Domain\UseCases\Movies;

use App\Infrastructure\Providers\TheMovieDbApiProvider;

class GetMoviesUseCase
{

    public function __construct(protected TheMovieDbApiProvider $theMovieDB) {}

    public function execute(array $data): array
    {
        $movies = $this->theMovieDB->searchMovies($data['query'], $data['page']);

        return $movies;
    }
}
