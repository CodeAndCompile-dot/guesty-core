<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcalEvent extends Model
{
    protected $table = 'ical_events';

    protected $fillable = [
        'ppp_id',
        'ical_link',
        'start_date',
        'end_date',
        'text',
        'event_pid',
        'cat_id',
        'uid',
        'event_type',
        'booking_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relationships                                                      */
    /* ------------------------------------------------------------------ */

    public function property()
    {
        return $this->belongsTo(Property::class, 'event_pid');
    }

    public function importList()
    {
        return $this->belongsTo(IcalImportList::class, 'cat_id');
    }
}
