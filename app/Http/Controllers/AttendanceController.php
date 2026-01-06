<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_attendance')->only(['index','show']);
        // $this->middleware('permission:view_new_party')->only(['newPartyList']);
        // $this->middleware('permission:edit_party_visit')->only(['edit','update']);
        // $this->middleware('permission:delete_party_visit')->only(['destroy']);
    }
    // public function index(Request $request)
    // {
    //     $authUser = auth()->user();

    //     // Selected month (default = current)
    //     $month = $request->input('month', Carbon::now()->format('Y-m'));
    //     $startDate = Carbon::parse($month . '-01')->startOfMonth();
    //     $endDate = (clone $startDate)->endOfMonth();
    //     $today = Carbon::now()->format('Y-m-d');

    //     $usersQuery = User::query();
    //     $users = $usersQuery->orderBy('name')->get();

    //     // Selected user
    //     $selectedUserId = $request->input('user_id', $authUser->id);


    //     // Fetch trips for the selected user & month
    //     $trips = Trip::where('user_id', $selectedUserId)
    //         ->whereBetween('trip_date', [$startDate, $endDate])
    //         ->orderBy('trip_date')
    //         ->get()
    //         ->groupBy(function ($trip) {
    //             return Carbon::parse($trip->trip_date)->format('Y-m-d');
    //         });

    //     $attendanceData = [];

    //     // Build calendar data day by day
    //     $currentDate = (clone $startDate);
    //     $lastDate = (clone $endDate);

    //     while ($currentDate <= $lastDate) {
    //         $dateKey = $currentDate->format('Y-m-d');

    //         if (isset($trips[$dateKey]) && $trips[$dateKey]->isNotEmpty()) {
    //             $trip = $trips[$dateKey]->first(); // pick first trip of the day

    //             if ($trip->approval_status === 'approved') {
    //                 $status = 'Present';
    //             } elseif ($trip->approval_status === 'pending') {
    //                 $status = 'Pending';
    //             } elseif ($trip->approval_status === 'denied') {
    //                 $status = 'Absent';
    //             } else {
    //                 $status = 'N.A.';
    //             }

    //             $attendanceData[$dateKey] = [
    //                 'status' => $status,
    //                 'trip_type' => $trip->trip_type ?? '-',
    //                 'checkin' => $trip->start_time ? Carbon::parse($trip->start_time)->format('H:i') : '--',
    //                 'checkout' => $trip->end_time ? Carbon::parse($trip->end_time)->format('H:i') : '--'
    //             ];
    //         } elseif ($dateKey > $today) {
    //             $attendanceData[$dateKey] = [
    //                 'status' => 'N.A.',
    //                 'trip_type' => '-',
    //                 'checkin' => '--',
    //                 'checkout' => '--'
    //             ];
    //         } else {
    //             $attendanceData[$dateKey] = [
    //                 'status' => 'Absent',
    //                 'trip_type' => '-',
    //                 'checkin' => '--',
    //                 'checkout' => '--'
    //             ];
    //         }

    //         $currentDate->addDay();
    //     }

    //     return view('admin.hr.attendance.index_new', compact(
    //         'attendanceData',
    //         'startDate',
    //         'endDate',
    //         'month',
    //         'users',
    //         'selectedUserId'
    //     ));
    // }


    // public function index(Request $request)
    // {
    //     $month = $request->input('month', now()->format('Y-m'));

    //     $startDate = Carbon::parse($month . '-01')->startOfMonth();
    //     $endDate   = Carbon::parse($month . '-01')->endOfMonth();
    //     $today     = now()->format('Y-m-d');

    //     $users = User::orderBy('name')->get();

    //     $trips = Trip::whereBetween('trip_date', [$startDate, $endDate])
    //         ->get()
    //         ->groupBy(function ($trip) {
    //             return $trip->user_id . '_' . Carbon::parse($trip->trip_date)->format('Y-m-d');
    //         });
    //     $leaves = Leave::where('status',1)->get();
    //     $holidays = Holiday::pluck('holiday_date')->toArray();
    //     $attendance = [];

    //     foreach ($users as $user) {
    //         $currentDate = $startDate->copy();
    //         while ($currentDate <= $endDate) {
    //             $dateKey = $currentDate->format('Y-m-d');

    //             // ✅ HOLIDAY FIRST PRIORITY
    //             if (in_array($dateKey, $holidays)) {
    //                 $status = 'H';
    //             }
    //             // ✅ SUNDAY
    //             elseif ($currentDate->isSunday()) {
    //                 $status = 'WO';
    //             }
    //             else {
    //                 $tripKey = $user->id . '_' . $dateKey;

    //                 if (isset($trips[$tripKey])) {
    //                     $trip = $trips[$tripKey]->first();

    //                     if ($trip->approval_status === 'approved') {
    //                         $status = $trip->trip_type === 'full'
    //                             ? 'P (Full)'
    //                             : 'P (Half)';
    //                     } else {
    //                         $status = 'A';
    //                     }
    //                 } elseif ($dateKey > $today) {
    //                     $status = 'NA';
    //                 } else {
    //                     $status = 'A';
    //                 }
    //             }

    //             $attendance[$user->id][$dateKey] = $status;
    //             $currentDate->addDay();
    //         }

    //     }
    //     return view('admin.hr.attendance.index_nnew', compact(
    //         'users',
    //         'attendance',
    //         'startDate',
    //         'endDate',
    //         'month',
    //         'leaves',
    //         'holidays'
    //     ));
    // }

    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate   = Carbon::parse($month . '-01')->endOfMonth();
        $today     = now()->format('Y-m-d');

        $users = User::orderBy('name')->get();

        // Trips
        $trips = Trip::whereBetween('trip_date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($trip) =>
                $trip->user_id.'_'.Carbon::parse($trip->trip_date)->format('Y-m-d')
            );

        // Leaves master
        $leaves = Leave::where('status',1)->get(); // CL, SL etc

        // Holidays
        $holidays = Holiday::pluck('holiday_date')->toArray();

        // Saved attendance (manual override)
        $savedAttendance = Attendance::whereBetween(
            'attendance_date', [$startDate, $endDate]
        )->get()->keyBy(fn($a) =>
            $a->user_id.'_'.$a->attendance_date->format('Y-m-d')
        );
        $attendance = [];

        foreach ($users as $user) {
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $dateKey = $currentDate->format('Y-m-d');
                $key = $user->id.'_'.$dateKey;
                // 🔐 MANUAL OVERRIDE FIRST
                if (isset($savedAttendance[$key])) {
                    $status = $savedAttendance[$key]->status;
                }
                elseif (in_array($dateKey, $holidays)) {
                    $status = 'H';
                }
                elseif ($currentDate->isSunday()) {
                    $status = 'WO';
                }
                else {
                    if (isset($trips[$key])) {
                        $trip = $trips[$key]->first();

                        if ($trip->approval_status === 'approved') {
                            $status = $trip->trip_type === 'full'
                                ? 'P_FULL'
                                : 'P_HALF';
                        } else {
                            $status = 'A';
                        }
                    }
                    elseif ($dateKey > $today) {
                        $status = 'NA';
                    }
                    else {
                        $status = 'A';
                    }
                }

                $attendance[$user->id][$dateKey] = $status;
                $currentDate->addDay();
            }
        }
        return view('admin.hr.attendance.index_nnew', compact(
            'users',
            'attendance',
            'startDate',
            'endDate',
            'month',
            'leaves'
        ));
    }

    public function save(Request $request){
        Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'attendance_date' => $request->date
            ],
            [
                'status' => $request->status
            ]
        );

        return response()->json(['success'=>true]);
    }


}
