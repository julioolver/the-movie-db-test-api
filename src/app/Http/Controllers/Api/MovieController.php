<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\Movie\CreateMovieStatusUseCase;
use App\Domain\UseCases\Movie\GetMoviesUseCase;
use App\Domain\UseCases\Movie\updateMovieStatusUseCase;
use App\Domain\UseCases\Movie\GetUserMoviesUseCase;
use App\Domain\UseCases\Movie\GetMovieDetailsUseCase;
use App\Dtos\UpdateMovieStatusDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Movie\StoreMovieRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentação da API",
 *      description="API para gerenciar filmes e usuários",
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Servidor da API"
 * )
 */
class MovieController extends Controller
{
    public function __construct(
        protected GetMoviesUseCase $getMoviesUseCase,
        protected CreateMovieStatusUseCase $createMovieStatusUseCase,
        protected updateMovieStatusUseCase $updateUserMovieStatusUseCase,
        protected GetUserMoviesUseCase $getUserMoviesUseCase,
        protected GetMovieDetailsUseCase $getMovieDetailsUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/movies",
     *     tags={"Filmes"},
     *     summary="Listar todos os filmes",
     *     description="Retorna uma lista de filmes",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Movie")
     *         ),
     *     ),
     *     @OA\Response(response=401, description="Não autorizado")
     * )
     */
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


    /**
     * @OA\Post(
     *     path="/api/movies",
     *     summary="Create a new movie",
     *     tags={"Filmes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Movie")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movie created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Movie created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Movie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
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
                watched: $request->input('watched', false),
                favorite: $request->input('favorite', false),
                watchLater: $request->input('watch_later', false)
            );

            $createdMovie = $this->createMovieStatusUseCase->execute($movie);

            return response()->json(['movie' => $createdMovie], HttpResponse::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/movies",
     *     summary="Update movie status",
     *     tags={"Filmes"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Movie")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movie status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Movie status updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Movie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function updateStatus(Request $request, int $movieId): JsonResponse
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'watched' => 'required|boolean',
                'favorite' => 'required|boolean',
                'watch_later' => 'required|boolean',
            ]);

            $this->updateUserMovieStatusUseCase->execute($user->id, $movieId, $validatedData);

            return response()->json(['message' => 'Status do filme atualizado com sucesso.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUserMovies(): JsonResponse
    {
        try {
            $movies = $this->getUserMoviesUseCase->execute();

            return response()->json(['movies' => $movies]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMovieDetails(string $id): JsonResponse
    {
        try {
            $movie = $this->getMovieDetailsUseCase->execute($id);

            return response()->json([
                'title' => $movie->getTitle(),
                'director' => $movie->getDirector(),
                'synopsis' => $movie->getSynopsis(),
                'duration' => $movie->getDuration(),
                'year' => $movie->getYear(),
                'posterPath' => $movie->getPosterPath(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
    }
}
