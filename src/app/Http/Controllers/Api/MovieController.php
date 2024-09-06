<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\Movies\GetMoviesUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class MovieController extends Controller
{
    public function __construct(protected GetMoviesUseCase $getMoviesUseCase) {}

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
}
