<?php

namespace App\Domain\UseCases\Movie;

use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Entities\Movie;

class GetMovieDetailsUseCase
{
    protected MovieRepositoryInterface $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function execute(string $externalId): ?Movie
    {
        $movie = $this->movieRepository->findByExternalId($externalId);

        if (!$movie) {
            throw new \Exception('Movie not found');
        }

        return $movie;
    }
}
