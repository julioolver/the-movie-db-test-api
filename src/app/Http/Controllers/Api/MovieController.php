<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\Movies\CreateMovieStatusUseCase;
use App\Domain\UseCases\Movies\GetMoviesUseCase;
use App\Dtos\UpdateMovieStatusDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreMovieRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class MovieController extends Controller
{
    public function __construct(protected GetMoviesUseCase $getMoviesUseCase, protected CreateMovieStatusUseCase $createMovieStatusUseCase) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query', '');
            $page = $request->input('page', 1);

            $movies = $this->getMoviesUseCase->execute([
                'query' => $query,
                'page' => $page
            ]);

            return response()->json($movies);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreMovieRequest $request): JsonResponse
    {
        try {
            $movie = new UpdateMovieStatusDto(
                externalId: $request->input('external_id'),
                provider: $request->input('provider'),
                title: $request->input('title'),
                director: $request->input('director'),
                synopsis: $request->input('synopsis'),
                duration: $request->input('duration'),
                year: $request->input('year'),
                posterPath: $request->input('poster_path'),
                isWatched: $request->input('is_watched', false),
                isFavorite: $request->input('is_favorite', false),
                watchLater: $request->input('watch_later', false)
            );

            $this->createMovieStatusUseCase->execute($movie);

            return response()->json([
                'message' => 'Movie created successfully'
            ], HttpResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
