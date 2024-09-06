<?php

namespace App\Domain\Entities;

class Movie
{
    private $id;
    private $title;
    private $director;
    private $synopsis;
    private $duration;
    private $releaseYear;

    public function __construct(int $id, string $title, string $director, string $synopsis, int $duration, int $releaseYear)
    {
        $this->id = $id;
        $this->title = $title;
        $this->director = $director;
        $this->synopsis = $synopsis;
        $this->duration = $duration;
        $this->releaseYear = $releaseYear;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'director' => $this->director,
            'synopsis' => $this->synopsis,
            'duration' => $this->duration,
            'releaseYear' => $this->releaseYear
        ];
    }
}
