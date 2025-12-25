<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PartyPayment;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class PartyPaymentController extends Controller
{
    public function index(Request $request){
        // $data = PartyPayment::with('customer','user')->get();
        $company = Company::first();
        $query = PartyPayment::with(['customer', 'user']);
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

        $data = $query->latest()->get();
        $employees = User::where('status','Active')->get();
        $companyCount = Company::count();
        $company = null;

        if ($companyCount == 1) {
            $company = Company::first();

            if ($company && !empty($company->state)) {
                $companyStates = array_map('intval', explode(',', $company->state));

                $states = State::where('status', 1)
                    ->whereIn('id', $companyStates)
                    ->get();
            } else {
                $states = State::where('status', 1)->get();
            }
        } else {
            $states = State::where('status', 1)->get();
        }

        return view('admin.party_payment.index',compact('data','states','employees'));
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
