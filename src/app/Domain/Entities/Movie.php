<?php

namespace App\Domain\Entities;

class Movie
{
    private ?int $id;
    private string $externalId;
    private string $provider;
    private string $title;
    private ?string $director;
    private ?string $synopsis;
    private ?int $duration;
    private ?int $year;
    private ?string $posterPath;

    public function __construct(
        ?int $id = null,
        string $externalId,
        string $provider,
        string $title,
        ?string $director = null,
        ?string $synopsis = null,
        ?int $duration = null,
        ?int $year = null,
        ?string $posterPath = null
    ) {
        $this->id = $id;
        $this->externalId = $externalId;
        $this->provider = $provider;
        $this->title = $title;
        $this->director = $director;
        $this->synopsis = $synopsis;
        $this->duration = $duration;
        $this->year = $year;
        $this->posterPath = $posterPath;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->externalId,
            'provider' => $this->provider,
            'title' => $this->title,
            'director' => $this->director,
            'synopsis' => $this->synopsis,
            'duration' => $this->duration,
            'year' => $this->year,
            'poster_path' => $this->posterPath
        ];
    }
}
