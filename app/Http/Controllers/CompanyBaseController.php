<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CompanyBaseController extends Controller
{
    protected function applyCompanyScope($query)
    {
        $user = Auth::user();

        if ($user->user_level === 'master_admin') {
            return $query; // Don't filter for master admin
        }

        return $query->where('company_id', $user->company_id);
    }
}
