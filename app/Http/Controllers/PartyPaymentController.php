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
            $employees = User::where('status', 'Active')->where('id', '!=', 1)->get();
        } else {
            $employees = empty($stateIds)
                ? collect()
                : User::where('status', 'Active')->where('id', '!=', 1)
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

        $payment = PartyPayment::with(['user', 'customer'])->findOrFail($request->id);

        $payment->update([
            'clear_return_date' => $request->clear_return_date,
            'status' => 'payment received',
        ]);

        $this->sendPaymentReceivedNotification($payment);

        return redirect()->back()->with('success', 'Payment marked as received successfully.');
    }

    private function sendPaymentReceivedNotification(PartyPayment $payment): void
    {
        try {
            $payment->loadMissing(['user', 'customer']);

            if (!$payment->user || empty($payment->user->fcm_token)) {
                return;
            }

            $firebaseService = app(\App\Services\FirebaseService::class);
            $partyName = $payment->customer->agro_name ?? 'Party payment';
            $title = 'Payment Received';
            $message = "Payment for {$partyName} has been marked as received.";

            $firebaseService->sendNotification($payment->user->fcm_token, $title, $message, [
                'type' => 'party_payment_status',
                'party_payment_id' => (string) $payment->id,
                'customer_id' => (string) $payment->customer_id,
                'status' => $payment->status,
                'amount' => (string) $payment->amount,
                'clear_return_date' => (string) ($payment->clear_return_date ?? ''),
            ], $payment->user->id);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send party payment notification: ' . $e->getMessage());
        }
    }
}
