<?php

namespace App\Infrastructure\Persistence\Movie;

use App\Domain\Entities\Movie;
use App\Domain\Entities\User;
use App\Domain\Repositories\MovieRepositoryInterface;
use App\Infrastructure\Persistence\BaseEloquentRepository;
use App\Models\Movie as MovieEloquent;

class MovieEloquentRepository extends BaseEloquentRepository implements MovieRepositoryInterface
{
    public function __construct(protected MovieEloquent $movieEloquent) {}

    public function findById(int $id): ?Movie
    {
        $movie = $this->findEloquentModelById($id);

        return $movie ? new Movie($movie->externalId, $movie->provider, $movie->title, $movie->director, $movie->synopsis, $movie->duration, $movie->year, $movie->posterPath) : null;
    }

    public function findByExternalId(string $externalId): ?Movie
    {
        return $this->movieEloquent->where('external_id', $externalId)->first();
    }
    public function findByTitle(string $title): ?Movie
    {
        return $this->movieEloquent->where('title', $title)->first();
    }
    public function create(Movie $movie): Movie
    {
        $newMovie = $this->movieEloquent->create($movie->toArray());
        $movie->setId($newMovie->id);

        return $movie;
    }
    public function createUserMovieStatus(User $user, Movie $movie, array $status): void
    {
        $movie = $this->findEloquentModelById($movie->getId());

        $movie->users()->attach($user->getId(), $status);
    }
}
