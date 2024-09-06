<?php

namespace App\Http\Controllers\Api;

use App\Domain\UseCases\AuthenticateUser;
use App\Domain\UseCases\RegisterUser;
use Illuminate\Auth\AuthenticationException;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

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

    public function login(LoginRequest $request)
    {
        try {
            $userLogin = $request->only(['email', 'password']);

            $authToken = $this->authenticateUser->execute($userLogin);

            return $this->respondWithToken($authToken);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->registerUser->execute($request->validated());

            return response()->json([
                'user' => $user,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
