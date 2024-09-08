<?php

namespace App\Infrastructure\Cache;

use Illuminate\Support\Facades\Cache;

class MovieCacheService
{
    public function getMoviesFromApiWithCache($page, $serchTerm, $apiCallback)
    {
        $cacheKey = "movies_page_{$page}_" . md5($serchTerm);


        return Cache::remember($cacheKey, 60 * 30, fn() => $apiCallback($page, $serchTerm));
    }

    public function getUserMoviesFromCache($userId, $dbCallback)
    {
        $cacheKey = 'user_movies_' . $userId;

        return Cache::remember($cacheKey, 60 * 30, fn() => $dbCallback($userId));
    }


    public function clearUserMoviesCache($userId)
    {
        $cacheKey = 'user_movies_' . $userId;
        Cache::forget($cacheKey);
    }

    public function clearMoviesApiCacheForSearchTerm($serchTerm)
    {
        foreach (range(1, 10) as $page) {
            $cacheKey = "movies_page_{$page}_" . md5($serchTerm);
            Cache::forget($cacheKey);
        }
    }
}
