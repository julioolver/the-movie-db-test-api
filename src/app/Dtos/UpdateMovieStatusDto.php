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
    public ?string $year;
    public ?string $posterPath;
    public bool $watched;
    public bool $favorite;
    public bool $watchLater;

    public function __construct(
        string $externalId,
        string $provider,
        string $title,
        ?string $director,
        ?string $synopsis,
        ?int $duration,
        ?string $year,
        ?string $posterPath,
        bool $watched,
        bool $favorite,
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
        $this->watched = $watched;
        $this->favorite = $favorite;
        $this->watchLater = $watchLater;
    }
}
