<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brochure;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PriceList;
use Carbon\Carbon;


class CommanController extends Controller
{
    public function priceList(Request $request)
    {
        $user = Auth::user();
        $query = PriceList::with('state:id,name');

        if ($user->state_id) {
            $query->where('state_id', $user->state_id);
        }

        $prices = $query->latest()->get()->map(function ($item) {
            return [
                'id'        => $item->id,
                'date'      => $item->created_at
                                    ->timezone('Asia/Kolkata')
                                    ->format('d-m-Y'),
                'state'     => $item->state->name ?? '',
                'pdf_url'   => asset('storage/'.$item->pdf_path),
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $prices
        ]);
    }

    public function brochures(Request $request)
    {
        $query = Brochure::with('state:id,name');

        // if ($request->filled('state_id')) {
        //     $query->where('state_id', $request->state_id);
        // }

        $brochures = $query->latest()->get()->map(function ($item) {
            return [
                'id'        => $item->id,
                'date'      => $item->created_at
                                    ->timezone('Asia/Kolkata')
                                    ->format('d-m-Y'),
                'state'     => $item->state->name ?? '',
                'pdf_url'   => asset('storage/'.$item->pdf_path),
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $brochures
        ]);
    }

    public function messages()
    {
        $userId = Auth::id(); // current logged-in user

        $messages = Message::whereDate('created_at', '>=', Carbon::now()->subDays(5))
            ->where(function ($query) use ($userId) {
                $query->where('type', 'all')
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('type', 'individual')
                            ->where('user_id', $userId);
                    });
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id'      => $item->id,
                    'title'   => $item->title,
                    'message' => $item->message,
                    'date'    => $item->created_at
                                    ->timezone('Asia/Kolkata')
                                    ->format('d-m-Y'),
                ];
            });

        return response()->json([
            'status' => true,
            'data'   => $messages
        ]);
    }
}
