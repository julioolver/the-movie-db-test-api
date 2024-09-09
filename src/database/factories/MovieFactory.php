<?php

namespace Database\Factories;

use App\Domain\Entities\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Model>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }

    public static function makeMovie(): Movie
    {
        return new Movie(
            id: fake()->numberBetween(1000, 9999),
            externalId: fake()->numberBetween(100, 999),
            provider: fake()->text(10),
            title: fake()->title(),
            director: fake()->name(),
            synopsis: fake()->sentence(),
            duration: fake()->numberBetween(60, 180),
            year: fake()->year(),
            posterPath: fake()->imageUrl(),
        );
    }
}
