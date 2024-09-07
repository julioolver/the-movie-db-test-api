<?php

namespace App\Infrastructure\Persistence\Movie;

use App\Domain\Entities\Movie;
use App\Domain\Entities\User;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\BaseEloquentRepository;
use App\Models\Movie as MovieEloquent;

class MovieEloquentRepository extends BaseEloquentRepository implements MovieRepositoryInterface
{
    protected MovieEloquent $eloquentModel;

    public function __construct(MovieEloquent $eloquentModel)
    {
        $this->eloquentModel = $eloquentModel;
        parent::__construct($eloquentModel);
    }

    public function findById(int $id): ?Movie
    {
        $movie = $this->findEloquentModelById($id);

        return $movie ? new Movie($movie->externalId, $movie->provider, $movie->title, $movie->director, $movie->synopsis, $movie->duration, $movie->year, $movie->posterPath) : null;
    }

    public function findByExternalId(string $externalId): ?Movie
    {
        $movie = $this->eloquentModel->where('external_id', $externalId)->first();

        return $movie ? new Movie(
            id: $movie->id,
            externalId: $movie->external_id,
            provider: $movie->provider,
            title: $movie->title,
            director: $movie->director,
            synopsis: $movie->synopsis,
            duration: $movie->duration,
            year: $movie->year,
            posterPath: $movie->poster_path
        ) : null;
    }
    public function findByTitle(string $title): ?Movie
    {
        return $this->eloquentModel->where('title', $title)->first();
    }
    public function create(Movie $movie): Movie
    {
        $newMovie = $this->eloquentModel->create($movie->toArray());
        $movie->setId($newMovie->id);

        return $movie;
    }
    public function createUserMovieStatus(User $user, Movie $movie, array $status): void
    {
        $movie = $this->findEloquentModelById($movie->getId());

        $movie->users()->attach($user->getId(), $status);
    }
}
