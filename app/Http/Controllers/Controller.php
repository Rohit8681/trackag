<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\UserStateAccess;
use App\Models\Company;
use App\Models\User;
use App\Models\State;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getRoleBasedStateAndEmployeeFilters()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();
        
        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
        }

        $companyCount = Company::count();
        $company = null;
        $companyStates = [];

        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $employees = User::where('status', 'Active')->where('id', '!=', 1)->get();
        } else {
            $employees = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')->where('id', '!=', 1)
                    ->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id)
                    ->get();
        }

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));
                if (in_array($roleName, ['sub_admin'])) {
                    $states = State::where('status', 1)
                    ->whereIn('id', $companyStates)
                    ->get();
                } else {
                    $states = empty($stateIds)
                        ? collect()
                        : State::where('status', 1)
                        ->whereIn('id', $stateIds)
                        ->get();
                }
            } else {
                $states = in_array($roleName, ['master_admin', 'sub_admin']) 
                        ? State::where('status', 1)->get()
                        : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }
        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin']) 
                    ? State::where('status', 1)->get()
                    : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }

        return [
            'user' => $user,
            'roleName' => $roleName,
            'stateIds' => $stateIds,
            'company' => $company,
            'employees' => $employees,
            'states' => $states
        ];
    }
}