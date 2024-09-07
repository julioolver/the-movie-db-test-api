<?php

namespace App\Dtos;

class UpdateMovieStatusDto
{
    public string $externalId;
    public string $provider;
    public string $title;
    public ?string $director;
    public ?string $synopsis;
    public ?int $duration;
    public ?int $year;
    public ?string $posterPath;
    public bool $isWatched;
    public bool $isFavorite;
    public bool $watchLater;

    public function __construct(
        string $externalId,
        string $provider,
        string $title,
        ?string $director,
        ?string $synopsis,
        ?int $duration,
        ?int $year,
        ?string $posterPath,
        bool $isWatched,
        bool $isFavorite,
        bool $watchLater
    ) {
        $this->externalId = $externalId;
        $this->provider = $provider;
        $this->title = $title;
        $this->director = $director;
        $this->synopsis = $synopsis;
        $this->duration = $duration;
        $this->year = $year;
        $this->posterPath = $posterPath;
        $this->isWatched = $isWatched;
        $this->isFavorite = $isFavorite;
        $this->watchLater = $watchLater;
    }
}
