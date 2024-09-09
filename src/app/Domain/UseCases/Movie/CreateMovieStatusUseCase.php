<?php

namespace App\Domain\UseCases\Movie;

use App\Domain\Entities\Movie;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Dtos\UpdateMovieStatusDto;
use App\Infrastructure\Cache\MovieCacheService;
use Illuminate\Support\Facades\Auth;

class CreateMovieStatusUseCase
{

    public function __construct(
        protected MovieRepositoryInterface $movieRepository,
        protected UserRepositoryInterface $userRepository,
        protected MovieCacheService $movieCacheService
    ) {}

    /**
     * @param UpdateMovieStatusDto $dto
     *
     * @return void
     */

    public function execute(UpdateMovieStatusDto $dto): void
    {
        $user = $this->userRepository->findById(7); //Auth::id()

        if (!$user) {
            throw new \Exception('User not found');
        }

        $movie = $this->movieRepository->findByExternalId($dto->externalId);

        if ($movie) {
            throw new \Exception('Movie already exists');
        }

        $movie = $this->movieRepository->create(
            new Movie(
                id: null,
                externalId: $dto->externalId,
                provider: $dto->provider,
                title: $dto->title,
                director: $dto->director,
                synopsis: $dto->synopsis,
                duration: $dto->duration,
                year: $dto->year,
                posterPath: $dto->posterPath
            )
        );

        $this->movieRepository->createUserMovieStatus($user, $movie, [
            'watched' => $dto->isWatched,
            'favorite' => $dto->isFavorite,
            'watch_later' => $dto->watchLater
        ]);

        $this->movieCacheService->clearUserMoviesCache($user->getId());
    }
}
