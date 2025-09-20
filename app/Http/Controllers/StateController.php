<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStateRequest;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $query = State::with('country');

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $states = $query->orderBy('id', 'desc')->get();
        $countries = Country::orderBy('name')->get();

        return view('admin.state.index', compact('states', 'countries'));
    }

    public function create()
    {
        $countries = Country::get();
        return view('admin.state.create', compact('countries'));
    }

    public function store(StoreStateRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $request->has('status') ? 1 : 0;

        State::create($data);

        return redirect()
            ->route('states.index')
            ->with('success', 'State created successfully.');
    }

    public function show(State $state)
    {
        //
    }

    
    public function edit(State $state)
    {
        $countries = Country::get();
        $state = $state->find($state->id);
        return view('admin.state.edit',compact('countries','state'));
    }
    
    public function update(StoreStateRequest $request, State $state)
    {
        $data = $request->validated();
        $data['status'] = $request->has('status') ? 1 : 0;
        $state->update($data);

        return redirect()
            ->route('states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        //
    }
}
