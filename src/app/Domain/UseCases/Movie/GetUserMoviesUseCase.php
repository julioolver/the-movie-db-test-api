<?php

namespace App\Domain\UseCases\Movie;

use App\Domain\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Cache\MovieCacheService;
use Illuminate\Support\Facades\Auth;

class GetUserMoviesUseCase
{
    public function __construct(
        protected MovieRepositoryInterface $movieRepository,
        protected MovieCacheService $movieCacheService
    ) {}

    /**
     * Executa a listagem dos filmes associados ao usuário
     *
     * @return array
     * @throws \Exception
     */
    public function execute()
    {
        $user = Auth::user();

        return $this->movieCacheService->getUserMoviesFromCache($user->id, fn() => $this->movieRepository->getPersistedMoviesForUser($user));
    }
}
