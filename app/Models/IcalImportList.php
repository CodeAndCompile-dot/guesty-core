<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcalImportList extends Model
{
    protected $table = 'ical_import_list';

    protected $fillable = [
        'ical_link',
        'property_id',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function events()
    {
        return $this->hasMany(IcalEvent::class, 'cat_id');
    }
}
