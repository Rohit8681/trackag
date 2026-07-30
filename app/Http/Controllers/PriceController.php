<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PriceList;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class PriceController extends Controller
{
     public function index()
    {
        $prices = PriceList::with('state')->latest()->get();
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();
            $companyStates = array_map('intval', explode(',', $company->state));
            $states = State::where('status', 1)
                        ->whereIn('id', $companyStates)
                        ->get();
        }else{
            $states = State::where('status', 1)->get();
        }
        return view('admin.price.index', compact('prices', 'states'));
    }

    public function create()
    {
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();
            $companyStates = array_map('intval', explode(',', $company->state));
            $states = State::where('status', 1)->whereIn('id', $companyStates)->get();
        }else{
            $states = State::where('status', 1)->get();
        }
        return view('admin.price.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'pdf'      => 'required|mimes:pdf|max:5120',
        ]);

        $path = $request->file('pdf')->store('price_lists', 'public');

        $priceList = PriceList::create([
            'state_id' => $request->state_id,
            'pdf_path' => $path,
        ]);

        $this->sendPriceListNotification($priceList);

        return redirect()->route('price.index')->with('success', 'Price list uploaded successfully');
    }

    private function sendPriceListNotification(PriceList $priceList): void
    {
        try {
            $priceList->loadMissing('state');

            $users = User::where('state_id', $priceList->state_id)
                ->where('status', 'Active')
                ->whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->get();

            if ($users->isEmpty()) {
                return;
            }

            $firebaseService = app(\App\Services\FirebaseService::class);
            $stateName = $priceList->state->name ?? 'your state';
            $title = 'New Price List Uploaded';
            $message = "New price list has been uploaded for {$stateName}.";

            foreach ($users as $user) {
                $firebaseService->sendNotification($user->fcm_token, $title, $message, [
                    'type' => 'price_list',
                    'price_list_id' => (string) $priceList->id,
                    'state_id' => (string) $priceList->state_id,
                    'pdf_url' => asset('storage/' . $priceList->pdf_path),
                ], $user->id);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send price list notification: ' . $e->getMessage());
        }
    }

    public function show(PriceList $price)
    {
        return redirect(asset('storage/' . $price->pdf_path));
    }
}
