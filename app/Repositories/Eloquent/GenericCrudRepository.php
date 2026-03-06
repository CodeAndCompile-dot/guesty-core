<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Generic CRUD repository for simple admin entities.
 *
 * Instantiated with any Eloquent model class to provide standard
 * CRUD operations without needing a dedicated repository class.
 */
class GenericCrudRepository extends BaseRepository
{
    /**
     * Create a new GenericCrudRepository for the given model class.
     *
     * @param  class-string<Model>  $modelClass
     */
    public static function for(string $modelClass): static
    {
        return new static(new $modelClass);
    }
}
