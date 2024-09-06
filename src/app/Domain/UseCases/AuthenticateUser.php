<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Security\JwtAuthService;
use Illuminate\Auth\AuthenticationException;
use Exception;

class AuthenticateUser
{
    private UserRepositoryInterface $userRepository;
    private JwtAuthService $jwtAuthService;

    public function __construct(UserRepositoryInterface $userRepository, JwtAuthService $jwtAuthService)
    {
        $this->userRepository = $userRepository;
        $this->jwtAuthService = $jwtAuthService;
    }

    public function execute(array $credentials)
    {
        try {
            $user = $this->userRepository->findByEmail($credentials['email']);
            $user->setPassword($credentials['password']);


            $token = $this->jwtAuthService->generateToken($user);
            return response()->json(['token' => $token], 200);
        } catch (Exception $e) {
            throw new Exception("Error in user authentication: " . $e->getMessage(), 0, $e);
        }
    }
}
