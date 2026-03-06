<?php

namespace App\Support\Traits;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

trait HasDuplication
{
    /**
     * Duplicate a record, excluding primary key and timestamps.
     */
    protected function duplicateRecord(BaseRepositoryInterface $repository, int|string $id): Model
    {
        return $repository->duplicate($id);
    }
}
