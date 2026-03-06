<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EmailTemplateRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find an email template by its type slug.
     */
    public function findByType(string $type): ?Model;
}
