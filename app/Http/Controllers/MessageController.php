<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\State;
use App\Models\User;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::with(['state', 'user'])
        ->latest()
        ->get();

        return view('admin.message.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        
        $employees = User::where('status', 'Active')->get();
        return view('admin.message.create', compact('states','employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'     => 'required|in:all,individual',
            'state_id' => 'required|exists:states,id',
            'message'  => 'required|string',
            'user_id'  => 'nullable|exists:users,id',
        ]);

        if ($request->type === 'all') {

            Message::create([
                'type'     => $request->type,
                'state_id' => $request->state_id,
                'user_id'  => null,
                'message'  => $request->message,
            ]);

        } else {

            Message::create([
                'type'     => $request->type,
                'state_id' => $request->state_id,
                'user_id'  => $request->user_id,
                'message'  => $request->message,
            ]);
        }

        return redirect()
            ->route('messages.index')
            ->with('success', 'Message sent successfully.');
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
