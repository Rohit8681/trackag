<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;


class CropCategory extends Model
{
    use TenantConnectionTrait;
    protected $fillable = ['name', 'status'];

    public function subCategories()
    {
        return $this->hasMany(CropSubCategory::class);
    }
}
