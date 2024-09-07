<?php

namespace App\Domain\UseCases\Movies;

use App\Domain\Entities\Movie;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Dtos\UpdateMovieStatusDto;
use Illuminate\Support\Facades\Auth;

class createMovieStatusUseCase
{

    public function __construct(
        protected MovieRepositoryInterface $movieRepository,
        protected UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @param UpdateMovieStatusDto $dto
     *
     * @return void
     */

    public function execute(UpdateMovieStatusDto $dto): void
    {
        $user = $this->userRepository->findById(Auth::user()->id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $movie = $this->movieRepository->findByExternalId($dto->externalId);

        if (!$movie) {
            $movie = $this->movieRepository->create(
                new Movie(
                    externalId: $dto->externalId,
                    provider: $dto->provider,
                    title: $dto->title,
                    director: $dto->director,
                    synopsis: $dto->synopsis,
                    duration: $dto->duration,
                    year: $dto->year,
                    posterPath: $dto->posterPath
                    // $dto->isWatched,
                    // $dto->isFavorite,
                    // $dto->watchLater
                )
            );
        }

        $this->movieRepository->createUserMovieStatus($user, $movie, [
            'isWatched' => $dto->isWatched,
            'isFavorite' => $dto->isFavorite,
            'watchLater' => $dto->watchLater
        ]);
    }
}
