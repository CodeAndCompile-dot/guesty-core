<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface CmsRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find a CMS page by its SEO URL slug.
     */
    public function findBySeoUrl(string $slug): ?Model;
}
