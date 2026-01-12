<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Brochure;
use App\Models\Holiday;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PriceList;
use App\Models\Trip;
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

    public function myAttendance(Request $request)
    {
        $user = Auth::user();

        $year  = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();
        $today     = now()->format('Y-m-d');

        /* -------- Fetch Data -------- */
        $trips = Trip::where('user_id', $user->id)
            ->whereBetween('trip_date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($t) => Carbon::parse($t->trip_date)->format('Y-m-d'));

        $savedAttendance = Attendance::where('user_id', $user->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn($a) => $a->attendance_date->format('Y-m-d'));

        $holidays = Holiday::pluck('holiday_date')->toArray();

        /* -------- Counters -------- */
        $summary = [
            'P'  => 0,
            'A'  => 0,
            'PL' => 0,
            'CL' => 0,
            'SL' => 0,
        ];

        /* -------- Calendar -------- */
        $calendar = [];
        $date = $startDate->copy();

        while ($date <= $endDate) {
            $dateKey = $date->format('Y-m-d');

            if (isset($savedAttendance[$dateKey])) {
                $status = $savedAttendance[$dateKey]->status;
            }
            elseif (in_array($dateKey, $holidays)) {
                $status = 'H';
            }
            elseif ($date->isSunday()) {
                $status = 'WO';
            }
            elseif (isset($trips[$dateKey])) {
                $trip = $trips[$dateKey];
                $status = $trip->approval_status === 'approved'
                    ? ($trip->trip_type === 'full' ? 'P' : 'PL')
                    : 'A';
            }
            elseif ($dateKey > $today) {
                $status = 'NA';
            }
            else {
                $status = 'A';
            }

            if (isset($summary[$status])) {
                $summary[$status]++;
            }

            $calendar[] = [
                'date'        => $dateKey,
                'day'         => $date->format('D'),
                'status'      => $status,
                'is_today'    => $dateKey === $today,
                'is_weekend'  => $date->isSunday(),
                'is_holiday'  => in_array($dateKey, $holidays)
            ];

            $date->addDay();
        }

        return response()->json([
            'status'     => true,
            'month_year' => $startDate->format('F Y'),
            'summary'    => $summary,
            'calendar'   => $calendar
        ]);
    }

}
