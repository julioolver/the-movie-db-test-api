<?php

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\MovieApiRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Http;

class TheMovieDbApiProvider implements MovieApiRepositoryInterface
{
    private string $apiKey;
    private const BASE_URL = 'https://api.themoviedb.org/3/';

    public function __construct(protected Http $client)
    {
        $this->apiKey = config('services.themoviedb.api_key');
    }

    /**
     * Search movies by query using The Movie DB API.
     *
     * @param string $query
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function searchMovies(string $query, int $page = 1, array $persistedMovies = []): array
    {
        try {
            $response = $this->client::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ])->get(self::BASE_URL . 'search/movie', [
                'query' => $query,
                'page' => $page,
                'include_adult' => false,
                'language' => 'pt-BR',
            ]);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            $reusult = $response->json();

            return $this->respondMovies($reusult, $persistedMovies);
        } catch (Exception $e) {
            throw new Exception("Error retrieving movies from The Movie DB: " . $e->getMessage());
        }
    }

    public function respondMovies(array $movies, array $persistedMovies = []): array
    {
        $moviesMap = array_map(function ($movie) use ($persistedMovies) {
            $persistedStatus = $persistedMovies[$movie['id']] ?? [];

            return [
                'internal_id' => $movie['internal_id'] ?? null,
                'external_id' => $movie['id'],
                'title' => $movie['title'],
                'provider' => 'the-movie-db',
                'synopsis' => $movie['overview'],
                'year' => $movie['release_date'],
                'watched' => false,
                'favorite' => false,
                'watch_later' => false,
                'poster_path' => 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'],
                // $movie['director'] => $movie['original_title'];
                // $movie['duration'] => $movie['runtime'];
                ...$persistedStatus
            ];
        }, $movies['results']);

        return [
            'movies' => $moviesMap,
            'page' => [
                'total' => $movies['total_pages'],
                'current' => $movies['page'],
                'total_movies' => $movies['total_results'],
            ]
        ];
    }

    public function providesFullMovieDetails(): bool
    {
        // TMDB não fornece todos os detalhes diretamente na lista de filmes, então retorna falso para ser adaptado a outra integração
        return false;
    }

    public function getMovieDetails(string $externalId): array
    {
        try {
            $response = $this->client::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ])->get(self::BASE_URL . "movie/{$externalId}", [
                'language' => 'pt-BR',
            ]);

            // Extrai os detalhes do filme
            $movie = $response->json();

            $creditsResponse = $this->client::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ])->get(self::BASE_URL . "movie/{$externalId}/credits");

            if ($creditsResponse->failed()) {
                throw new Exception($creditsResponse->body());
            }

            $credits = $creditsResponse->json();
            $director = $this->extractDirector($credits);

            // Retorna os detalhes completos do filme
            return [
                'external_id' => $movie['id'],
                'title' => $movie['title'],
                'synopsis' => $movie['overview'],
                'duration' => $movie['runtime'],
                'year' => $movie['release_date'],
                'director' => $director
            ];
        } catch (Exception $e) {
            throw new Exception("Error retrieving movie details from The Movie DB: " . $e->getMessage());
        }
    }

    private function extractDirector(array $credits): string
    {
        foreach ($credits['crew'] as $crewMember) {
            if ($crewMember['job'] === 'Director') {
                return $crewMember['name'];
            }
        }

        return 'Diretor Desconhecido';
    }
}
