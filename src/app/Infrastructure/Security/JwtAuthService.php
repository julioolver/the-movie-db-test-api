<?php

namespace App\Infrastructure\Security;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Domain\Entities\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Exception;
use Illuminate\Auth\AuthenticationException;

class JwtAuthService
{
    /**
     * Generate JWT token for the authenticated user.
     *
     * @param User $user
     * @return string
     * @throws Exception
     */
    public function generateToken(User $user): string
    {
        if (!$token = Auth::attempt(['email' => $user->getEmail(), 'password' => $user->getPassword()])) {
            throw new AuthenticationException('Invalid credentials.');
        }

        return $token;
    }
}
