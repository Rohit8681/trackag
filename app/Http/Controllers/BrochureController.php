<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\State;
use Illuminate\Http\Request;

class BrochureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brochures = Brochure::with('state')->latest()->get();
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.brochure.index',compact('brochures','states'));
    }

    public function create()
    {
        $states = State::where('status', 1)->orderBy('name')->get();
        return view('admin.brochure.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'date'     => 'required|date',
            'state_id' => 'required|exists:states,id',
            'pdf'      => 'required|mimes:pdf|max:5120',
        ]);

        $path = $request->file('pdf')->store('brochures', 'public');

        Brochure::create([
            // 'date'     => $request->date,
            'state_id' => $request->state_id,
            'pdf_path' => $path,
        ]);

        return redirect()->route('brochure.index')
            ->with('success', 'Brochure uploaded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
