<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\Company;
use App\Models\State;
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

        Brochure::create([
            'state_id' => $request->state_id,
            'pdf_path' => $path,
        ]);

        return redirect()->route('brochure.index')->with('success', 'Brochure uploaded successfully');
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
