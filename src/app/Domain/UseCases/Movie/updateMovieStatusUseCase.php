<?php

namespace App\Domain\UseCases\Movie;

use App\Domain\Repositories\MovieRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Cache\MovieCacheService;

class updateMovieStatusUseCase
{
    public function __construct(
        protected MovieRepositoryInterface $movieRepository,
        protected UserRepositoryInterface $userRepository,
        protected MovieCacheService $movieCacheService
    ) {}

    /**
     * Executa a atualização do status do filme para o usuário
     *
     * @param int $userId
     * @param int $movieId
     * @param array $status
     * @return void
     * @throws \Exception
     */
    public function execute(int $userId, int $movieId, array $status): void
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \Exception('Usuário não encontrado.');
        }

        $movie = $this->movieRepository->findById($movieId);
        if (!$movie) {
            throw new \Exception('Filme não encontrado.');
        }

        $this->movieRepository->updateUserMovieStatus($user, $movie, $status);

        $this->movieCacheService->clearUserMoviesCache($user->getId());
    }
}
