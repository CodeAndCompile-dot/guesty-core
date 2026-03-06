<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseRepositoryInterface
{
    public function __construct(protected Model $model)
    {
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->newQuery()->paginate($perPage, $columns);
    }

    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->find($id, $columns);
    }

    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        return $this->model->newQuery()->findOrFail($id, $columns);
    }

    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->where($field, $value)->get($columns);
    }

    public function findFirstBy(string $field, mixed $value, array $columns = ['*']): ?Model
    {
        return $this->model->newQuery()->where($field, $value)->first($columns);
    }

    public function create(array $attributes): Model
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(int|string $id, array $attributes): bool
    {
        $record = $this->findOrFail($id);

        return $record->update($attributes);
    }

    public function delete(int|string $id): bool
    {
        $record = $this->findOrFail($id);

        return (bool) $record->delete();
    }

    public function allActive(array $columns = ['*']): Collection
    {
        return $this->model->newQuery()->where('status', 1)->get($columns);
    }

    public function activate(int|string $id): bool
    {
        return $this->update($id, ['status' => 1]);
    }

    public function deactivate(int|string $id): bool
    {
        return $this->update($id, ['status' => 0]);
    }

    public function duplicate(int|string $id): Model
    {
        $original = $this->findOrFail($id);

        $attributes = collect($original->getAttributes())
            ->except([$original->getKeyName(), 'created_at', 'updated_at'])
            ->toArray();

        return $this->create($attributes);
    }

    /**
     * Get the underlying model instance.
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
