<?php

namespace App\Domain\UseCases\User;

use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Domain\Entities\User;

class RegisterUser
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(array $data): array
    {
        try {
            $user = new User(null, $data['name'], $data['email'], Hash::make($data['password']));

            $savedUser = $this->userRepository->create($user);

            return $savedUser->toArray();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
