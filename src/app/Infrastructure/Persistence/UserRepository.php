<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Models\User as EloquentUser;

class UserRepository implements UserRepositoryInterface
{
    private EloquentUser $model;

    public function __construct(EloquentUser $model)
    {
        $this->model = $model;
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->model->where('email', $email)->first();
        return $user ? new User($user->id, $user->name, $user->email, $user->password) : null;
    }

    public function create(User $user): User
    {
        $newUser = $this->model->create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);

        return new User($newUser->id, $newUser->name, $newUser->email, $newUser->password);
    }
}
