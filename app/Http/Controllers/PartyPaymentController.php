<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PartyPayment;
use App\Models\State;
use App\Models\User;
use App\Models\UserStateAccess;
use Illuminate\Http\Request;

class PartyPaymentController extends Controller
{
    // public function index(Request $request){
    //     // $data = PartyPayment::with('customer','user')->get();
    //     $user = auth()->user();
    //     $roleName = $user->getRoleNames()->first();

    //     $stateIds = [];
    //     $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
    //     if ($userStateAccess && !empty($userStateAccess->state_ids)) {
    //         $stateIds = $userStateAccess->state_ids;
    //     }

    //     $company = Company::first();
    //     $query = PartyPayment::with(['customer', 'user']);

    //     if (!in_array($roleName, ['master_admin', 'sub_admin'])) {

    //         if (empty($stateIds)) {
    //             $customer = collect();

    //             return view('admin.new-party.index', compact('customer', 'users', 'states', 'company'));
    //         }

    //         $query->whereHas('user', function ($q) use ($user, $stateIds) {
    //             $q->whereIn('state_id', $stateIds)
    //             ->where('reporting_to', $user->id);
    //         });
    //     }
    //     if ($request->filled('from_date')) {
    //         $query->whereDate('payment_date', '>=', $request->from_date);
    //     }
    //     if ($request->filled('to_date')) {
    //         $query->whereDate('payment_date', '<=', $request->to_date);
    //     }
    //     if ($request->filled('user_id')) {
    //         $query->where('user_id', $request->user_id);
    //     }
        
    //     if ($request->filled('payment_status')) {
    //         $query->where('status', $request->payment_status);
    //     }

    //     if ($request->filled('state_id')) {
    //         $query->whereHas('user', function ($q) use ($request) {
    //             $q->where('state_id', $request->state_id);
    //         });
    //     }

    //     $data = $query->latest()->get();
        
    //     if (in_array($roleName, ['master_admin', 'sub_admin'])) {
    //         $employees = User::where('status','Active')->get();
    //     } else {
    //         $employees = empty($stateIds)
    //             ? collect()
    //             : User::where('status', 'Active')
    //                 ->whereIn('state_id', $stateIds)
    //                 ->where('reporting_to', $user->id)
    //                 ->get();
    //     }
    //     $companyCount = Company::count();
    //     $company = null;

    //     if ($companyCount == 1) {
    //         $company = Company::first();
    //         if ($company && !empty($company->state)) {
    //             $companyStates = array_map('intval', explode(',', $company->state));

    //             if ($roleName === 'sub_admin') {
    //                 $states = State::where('status', 1)
    //                     ->whereIn('id', $companyStates)
    //                     ->get();
    //             } else {
    //                 $states = empty($stateIds)
    //                     ? collect()
    //                     : State::where('status', 1)
    //                         ->whereIn('id', $stateIds)
    //                         ->get();
    //             }

    //         } else {
    //             $states = in_array($roleName, ['master_admin', 'sub_admin'])
    //                 ? State::where('status', 1)->get()
    //                 : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
    //         }

    //     } else {
    //         $states = in_array($roleName, ['master_admin', 'sub_admin'])
    //             ? State::where('status', 1)->get()
    //             : (empty($stateIds) ? collect() : State::where('status', 1)->whereIn('id', $stateIds)->get());
    //     }



    //     return view('admin.party_payment.index',compact('data','states','employees'));
    // }

    
    public function index(Request $request)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first();

        $stateIds = [];
        $userStateAccess = UserStateAccess::where('user_id', $user->id)->first();
        if ($userStateAccess && !empty($userStateAccess->state_ids)) {
            $stateIds = $userStateAccess->state_ids;
        }

        $query = PartyPayment::with(['customer', 'user']);

        if (!in_array($roleName, ['master_admin', 'sub_admin'])) {

            if (empty($stateIds)) {
                $data = collect();
            } else {
                $query->whereHas('user', function ($q) use ($user, $stateIds) {
                    $q->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id);
                });
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }

        if ($request->filled('state_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if (!isset($data)) {
            $data = $query->latest()->get();
        }

        if (in_array($roleName, ['master_admin', 'sub_admin'])) {
            $employees = User::where('status', 'Active')->get();
        } else {
            $employees = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')
                    ->whereIn('state_id', $stateIds)
                    ->where('reporting_to', $user->id)
                    ->get();
        }

        $companyCount = Company::count();
        $company = null;

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));

                if ($roleName === 'sub_admin') {
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
                    : (empty($stateIds)
                        ? collect()
                        : State::where('status', 1)->whereIn('id', $stateIds)->get());
            }
        } else {
            $states = in_array($roleName, ['master_admin', 'sub_admin'])
                ? State::where('status', 1)->get()
                : (empty($stateIds)
                    ? collect()
                    : State::where('status', 1)->whereIn('id', $stateIds)->get());
        }
        return view('admin.party_payment.index', compact('data', 'states', 'employees'));
    }

    public function clearReturn(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'clear_return_date' => 'required|date',
        ]);

        $payment = PartyPayment::findOrFail($request->id);

        $payment->update([
            'clear_return_date' => $request->clear_return_date,
            'status' => 'payment received',
        ]);

        return redirect()->back()->with('success', 'Payment marked as received successfully.');
    }
}
