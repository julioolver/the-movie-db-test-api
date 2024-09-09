<?php

namespace App\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

class BaseEloquentRepository
{
    private Model $eloquentModel;

    public function __construct(Model $eloquentModel)
    {
        $this->eloquentModel = $eloquentModel;
    }

    public function findEloquentModelById(int $id): ?Model
    {
        return $this->eloquentModel->find($id);
    }
}
