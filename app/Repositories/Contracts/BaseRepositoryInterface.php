<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Find a record by its primary key.
     */
    public function find(int|string $id, array $columns = ['*']): ?Model;

    /**
     * Find a record by its primary key or throw an exception.
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    /**
     * Find records matching a field value.
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection;

    /**
     * Find the first record matching a field value.
     */
    public function findFirstBy(string $field, mixed $value, array $columns = ['*']): ?Model;

    /**
     * Create a new record.
     */
    public function create(array $attributes): Model;

    /**
     * Update an existing record.
     */
    public function update(int|string $id, array $attributes): bool;

    /**
     * Delete a record.
     */
    public function delete(int|string $id): bool;

    /**
     * Get only active records.
     */
    public function allActive(array $columns = ['*']): Collection;

    /**
     * Activate a record (set status = 1).
     */
    public function activate(int|string $id): bool;

    /**
     * Deactivate a record (set status = 0).
     */
    public function deactivate(int|string $id): bool;

    /**
     * Duplicate a record.
     */
    public function duplicate(int|string $id): Model;
}
