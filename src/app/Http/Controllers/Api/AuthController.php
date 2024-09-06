<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Domain\UseCases\AuthenticateUser;
use App\Domain\UseCases\RegisterUser;
use App\Infrastructure\Security\JwtAuthService;
use Illuminate\Auth\AuthenticationException;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    private AuthenticateUser $authenticateUser;
    private RegisterUser $registerUser;
    private JwtAuthService $jwtAuthService;

    public function __construct(
        AuthenticateUser $authenticateUser,
        RegisterUser $registerUser,
        JwtAuthService $jwtAuthService
    ) {
        $this->authenticateUser = $authenticateUser;
        $this->registerUser = $registerUser;
        $this->jwtAuthService = $jwtAuthService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $userLogin = $request->only(['email', 'password']);

            return $this->authenticateUser->execute($userLogin);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            return $this->registerUser->execute($request->validated());
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
