<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\User\AuthenticateUser;
use App\Domain\UseCases\User\RegisterUser;
use Illuminate\Auth\AuthenticationException;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private AuthenticateUser $authenticateUser;
    private RegisterUser $registerUser;

    public function __construct(
        AuthenticateUser $authenticateUser,
        RegisterUser $registerUser,
    ) {
        $this->authenticateUser = $authenticateUser;
        $this->registerUser = $registerUser;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $userLogin = $request->only(['email', 'password']);

            $authToken = $this->authenticateUser->execute($userLogin);

            return $this->respondWithToken($authToken);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->registerUser->execute($request->validated());

            return response()->json([
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
