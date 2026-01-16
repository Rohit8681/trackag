<?php

namespace App\Models;
use App\Traits\TenantConnectionTrait;

use Illuminate\Database\Eloquent\Model;

class CropSubCategory extends Model
{
    use TenantConnectionTrait;
    protected $fillable = ['crop_category_id', 'name', 'status'];

    public function category()
    {
        return $this->belongsTo(CropCategory::class, 'crop_category_id');
    }
}
