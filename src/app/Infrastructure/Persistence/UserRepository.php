<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User;
use App\Models\User as EloquentUser;

class UserRepository extends BaseEloquentRepository implements UserRepositoryInterface
{
    private EloquentUser $eloquentModel;

    public function __construct(EloquentUser $eloquentModel)
    {
        $this->eloquentModel = $eloquentModel;
    }

    public function findById(int $id): ?User
    {
        $user = $this->eloquentModel->find($id);
        return $user ? new User($user->id, $user->name, $user->email, $user->password) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->eloquentModel->where('email', $email)->first();
        return $user ? new User($user->id, $user->name, $user->email, $user->password) : null;
    }

    public function create(User $user): User
    {
        $newUser = $this->eloquentModel->create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);

        return new User($newUser->id, $newUser->name, $newUser->email, $newUser->password);
    }
}
