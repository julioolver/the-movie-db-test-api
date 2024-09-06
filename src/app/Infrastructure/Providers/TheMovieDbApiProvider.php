<?php

namespace App\Infrastructure\Providers;

use Exception;
use Illuminate\Support\Facades\Http;

class TheMovieDbApiProvider
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
    public function searchMovies(string $query, int $page = 1): array
    {
        try {
            $response = $this->client->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'accept' => 'application/json',
            ])->get(self::BASE_URL . 'search/movie', [
                'query' => $query,
                'page' => $page,
                'include_adult' => false,
                'language' => 'en-US',
            ]);

            if ($response->failed()) {
                throw new Exception($response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception("Error retrieving movies from The Movie DB: " . $e->getMessage());
        }
    }
}
