<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PriceList;
use App\Models\State;
use Illuminate\Http\Request;

class PriceController extends Controller
{
     public function index()
    {
        $prices = PriceList::with('state')->latest()->get();
        // $states = State::orderBy('name')->get();
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
        // $states = State::orderBy('name')->get();
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
        return view('admin.price.create', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'pdf'      => 'required|mimes:pdf|max:5120',
        ]);

        $path = $request->file('pdf')->store('price_lists', 'public');

        PriceList::create([
            'state_id' => $request->state_id,
            'pdf_path' => $path,
        ]);

        return redirect()
            ->route('price.index')
            ->with('success', 'Price list uploaded successfully');
    }

    public function show(PriceList $price)
    {
        return redirect(asset('storage/' . $price->pdf_path));
    }
}
