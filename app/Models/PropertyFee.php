<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyFee extends Model
{
    use HasFactory;

    protected $table = 'property_fees';

    protected $fillable = [
        'property_id',
        'fee_name',
        'fee_rate',
        'fee_type',
        'fee_apply',
        'fee_status',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
