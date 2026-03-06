<?php

namespace App\Repositories\Eloquent;

use App\Models\EmailTemplete;
use App\Repositories\Contracts\EmailTemplateRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class EmailTemplateRepository extends BaseRepository implements EmailTemplateRepositoryInterface
{
    public function __construct(EmailTemplete $model)
    {
        parent::__construct($model);
    }

    public function findByType(string $type): ?Model
    {
        return $this->model->newQuery()
            ->where('email_type', $type)
            ->first();
    }
}
