<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaDaTourSlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'ta_da_slab_id',
        'tour_type_id',
        'da_amount',
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
