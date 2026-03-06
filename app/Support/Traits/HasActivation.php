<?php

namespace App\Support\Traits;

use App\Repositories\Contracts\BaseRepositoryInterface;

trait HasActivation
{
    /**
     * Activate a record by setting its status to 1.
     */
    protected function activateRecord(BaseRepositoryInterface $repository, int|string $id): bool
    {
        return $repository->activate($id);
    }

    /**
     * Deactivate a record by setting its status to 0.
     */
    protected function deactivateRecord(BaseRepositoryInterface $repository, int|string $id): bool
    {
        return $repository->deactivate($id);
    }
}
