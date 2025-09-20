<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PurposeController extends Controller
{
    public function index()
    {
        Session::put('page', '');

        $authUser = auth()->user();
        $purposes = $authUser->user_level === 'master_admin'
            ? purpose::with('company')->latest()->get()
            : purpose::with('company')->where('company_id', $authUser->company_id)->latest()->get();

        return view('admin.trips.purpose.index', compact('purposes'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $companies = $authUser->user_level === 'master_admin' ? Company::all() : collect();

        return view('admin.trips.purpose.create', compact('companies'));
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

        purpose::create($data);

        return redirect()->route('purpose.index')->with('success', 'Purpose created successfully.');
    }

    public function show(purpose $purpose)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $purpose->company_id !== $authUser->company_id) {
            abort(403);
        }

        return view('admin.trips.purpose.show', compact('purpose'));
    }

    public function edit(purpose $purpose)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $purpose->company_id !== $authUser->company_id) {
            abort(403);
        }

        $companies = $authUser->user_level === 'master_admin' ? Company::all() : collect();

        return view('admin.trips.purpose.edit', compact('purpose', 'companies'));
    }

    public function update(Request $request, purpose $purpose)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $purpose->company_id !== $authUser->company_id) {
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

        $purpose->update($data);

        return redirect()->route('purpose.index')->with('success', 'Purpose updated successfully.');
    }

    public function destroy(purpose $purpose)
    {
        $authUser = auth()->user();
        if ($authUser->user_level !== 'master_admin' && $purpose->company_id !== $authUser->company_id) {
            abort(403);
        }

        $purpose->delete();

        return redirect()->route('purpose.index')->with('success', 'Purpose deleted successfully.');
    }
}
