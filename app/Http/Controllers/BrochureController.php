<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\Company;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class BrochureController extends Controller
{
    public function index()
    {
        $brochures = Brochure::with('state')->latest()->get();
        $companyCount = Company::count();
        $company = null;
        if ($companyCount == 1) {
            $company = Company::first();
            $companyStates = array_map('intval', explode(',', $company->state));
            $states = State::where('status', 1)->whereIn('id', $companyStates)->get();
        }else{
            $states = State::where('status', 1)->get();
        }
        return view('admin.brochure.index',compact('brochures','states'));
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
        return view('admin.brochure.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'pdf'      => 'required|mimes:pdf|max:102400',
        ]);

        $path = $request->file('pdf')->store('brochures', 'public');

        $brochure = Brochure::create([
            'state_id' => $request->state_id,
            'pdf_path' => $path,
        ]);

        $this->sendBrochureNotification($brochure);

        return redirect()->route('brochure.index')->with('success', 'Brochure uploaded successfully');
    }

    private function sendBrochureNotification(Brochure $brochure): void
    {
        try {
            $brochure->loadMissing('state');

            $users = User::where('state_id', $brochure->state_id)
                ->where('status', 'Active')
                ->whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->get();

            if ($users->isEmpty()) {
                return;
            }

            $firebaseService = app(\App\Services\FirebaseService::class);
            $stateName = $brochure->state->name ?? 'your state';
            $title = 'New Brochure Uploaded';
            $message = "New brochure has been uploaded for {$stateName}.";

            foreach ($users as $user) {
                $firebaseService->sendNotification($user->fcm_token, $title, $message, [
                    'type' => 'brochure',
                    'brochure_id' => (string) $brochure->id,
                    'state_id' => (string) $brochure->state_id,
                    'pdf_url' => asset('storage/' . $brochure->pdf_path),
                ], $user->id);
            }
        } catch (\Exception $e) {
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
