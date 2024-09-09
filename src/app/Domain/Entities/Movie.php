<?php

namespace App\Domain\Entities;

/**
 * @OA\Schema(
 *     schema="Movie",
 *     type="object",
 *     title="Movie",
 *     description="Representação de um filme",
 *     required={"title", "year"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="O Poderoso Chefão"),
 *     @OA\Property(property="year", type="string", example="1972"),
 *     @OA\Property(property="director", type="string", example="Francis Ford Coppola"),
 *     @OA\Property(property="synopsis", type="string", example="A história da família Corleone..."),
 *     @OA\Property(property="poster_path", type="string", example="/path/to/poster.jpg"),
 *     @OA\Property(property="watched", type="boolean", example=false),
 *     @OA\Property(property="favorite", type="boolean", example=false),
 *     @OA\Property(property="watch_later", type="boolean", example=false),
 * )
 */
class Movie
{
    private ?int $id;
    private string $externalId;
    private string $provider;
    private string $title;
    private ?string $director;
    private ?string $synopsis;
    private ?int $duration;
    private ?string $year;
    private ?string $posterPath;

    public function __construct(
        ?int $id = null,
        string $externalId,
        string $provider,
        string $title,
        ?string $director = null,
        ?string $synopsis = null,
        ?int $duration = null,
        ?string $year = null,
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
