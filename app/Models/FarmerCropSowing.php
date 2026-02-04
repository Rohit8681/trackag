<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class FarmerCropSowing extends Model
{
    use HasFactory,TenantConnectionTrait;

    protected $table = 'farmer_crop_sowings';

    protected $fillable = [
        'farmer_id',
        'crop_sowing_id',
    ];

    public function crop()
    {
        return $this->belongsTo(CropSubCategory::class, 'crop_sowing_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

}