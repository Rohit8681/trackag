<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TenantConnectionTrait;

/**
 * Class TourType
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TourType extends Model
{
    use TenantConnectionTrait;
    protected $fillable = ['name', 'company_id'];

    /**
     * Get the company that owns the tour type.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
