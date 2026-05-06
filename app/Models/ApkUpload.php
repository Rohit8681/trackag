<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApkUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_code',
        'version_name',
        'whats_new',
        'file_path',
    ];
}
