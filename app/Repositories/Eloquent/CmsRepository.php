<?php

namespace App\Repositories\Eloquent;

use App\Models\Cms;
use App\Repositories\Contracts\CmsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CmsRepository extends BaseRepository implements CmsRepositoryInterface
{
    public function __construct(Cms $model)
    {
        parent::__construct($model);
    }

    public function findBySeoUrl(string $slug): ?Model
    {
        return $this->model->newQuery()
            ->where('seo_url', $slug)
            ->first();
    }
}
