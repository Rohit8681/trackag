<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

class FarmVisit extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $table = 'farmer_crop_sowings';
    protected $fillable = [
        'user_id',
        'farmer_id',
        'crop_id',
        'crop_days',
        'crop_sowing_land_area',
        'crop_condition',
        'pest_disease',
        'images',
        'video',
        'remark',
        'next_visit_date',
        'agronomist_remark'
    ];

    protected $casts = [
        'images' => 'array',
        'next_visit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    // Farmer relation
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    // Crop Sub Category relation
    public function crop()
    {
        return $this->belongsTo(CropSubCategory::class, 'crop_id');
    }
}
