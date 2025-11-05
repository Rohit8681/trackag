<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class TaDaTourSlab extends Model
{
    use HasFactory, TenantConnectionTrait;

    protected $fillable = [
        'ta_da_slab_id',
        'tour_type_id',
        'da_amount',
        'type',
        'user_id',
        'designation_id'
    ];

    // Relation to main slab
    public function slab()
    {
        return $this->belongsTo(TaDaSlab::class, 'ta_da_slab_id');
    }

    // Relation to tour type
    public function tourType()
    {
        return $this->belongsTo(TourType::class, 'tour_type_id');
    }
}
