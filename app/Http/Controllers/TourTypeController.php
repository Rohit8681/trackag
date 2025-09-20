<?php

namespace App\Http\Controllers;

use App\Models\TourType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TourTypeController extends Controller
{
    public function index()
    {
        Session::put('page', '');

        $authUser = auth()->user();
        $tourtypes = $authUser->user_level === 'master_admin'
            ? tourtype::with('company')->latest()->get()
            : tourtype::with('company')->where('company_id', $authUser->company_id)->latest()->get();

        return view('admin.trips.tourtype.index', compact('tourtypes'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $companies = $authUser->user_level === 'master_admin' ? Company::all() : collect();

        return view('admin.trips.tourtype.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => auth()->user()->user_level === 'master_admin' ? 'required|exists:companies,id' : '',
        ]);

        $data = $request->only('name');
        $authUser = auth()->user();

        $data['company_id'] = $authUser->user_level === 'master_admin'
            ? $request->company_id
            : $authUser->company_id;

        tourtype::create($data);

        return redirect()->route('tourtype.index')->with('success', 'Travel Mode created successfully.');
    }

    public function show(tourtype $tourtype)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $tourtype->company_id !== $authUser->company_id) {
            abort(403);
        }

        return view('admin.trips.tourtype.show', compact('tourtype'));
    }

    public function edit(tourtype $tourtype)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $tourtype->company_id !== $authUser->company_id) {
            abort(403);
        }

        $companies = $authUser->user_level === 'master_admin' ? Company::all() : collect();

        return view('admin.trips.tourtype.edit', compact('tourtype', 'companies'));
    }

    public function update(Request $request, tourtype $tourtype)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $tourtype->company_id !== $authUser->company_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => auth()->user()->user_level === 'master_admin' ? 'required|exists:companies,id' : '',
        ]);

        $data = $request->only('name');
        $data['company_id'] = $authUser->user_level === 'master_admin'
            ? $request->company_id
            : $authUser->company_id;

        $tourtype->update($data);

        return redirect()->route('tourtype.index')->with('success', 'Travel Mode updated successfully.');
    }

    public function destroy(tourtype $tourtype)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $tourtype->company_id !== $authUser->company_id) {
            abort(403);
        }

        $tourtype->delete();

        return redirect()->route('tourtype.index')->with('success', 'Travel Mode deleted.');
    }
}
