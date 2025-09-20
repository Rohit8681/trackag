<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();

        // Selected month (default to current)
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();
        $today = Carbon::now()->format('Y-m-d');

        // Fetch selectable users
        $usersQuery = User::query();
        if ($authUser->user_level !== 'master_admin') {
            $usersQuery->where('company_id', $authUser->company_id);
        }
        $users = $usersQuery->orderBy('name')->get();

        // Selected user (default to current logged-in user)
        $selectedUserId = $request->input('user_id', $authUser->id);

        // Check if selected user belongs to allowed company (if not master)
        if ($authUser->user_level !== 'master_admin') {
            $allowedUserIds = $users->pluck('id')->toArray();
            if (!in_array($selectedUserId, $allowedUserIds)) {
                abort(403, 'Unauthorized access to attendance.');
            }
        }

        // Fetch user sessions for selected user and month
        $sessions = UserSession::where('user_id', $selectedUserId)
            ->whereBetween('login_at', [$startDate, $endDate])
            ->orderBy('login_at')
            ->get()
            ->groupBy(function ($session) {
                return Carbon::parse($session->login_at)->format('Y-m-d');
            });

        // Build attendance data for each day
        $attendanceData = [];

        $currentDate = (clone $startDate)->startOfWeek(Carbon::SUNDAY);
        $lastDate = (clone $endDate)->endOfWeek(Carbon::SATURDAY);

        while ($currentDate <= $lastDate) {
            $dateKey = $currentDate->format('Y-m-d');

            if (isset($sessions[$dateKey])) {
                $firstSession = $sessions[$dateKey]->sortBy('login_at')->first();
                $lastSession = $sessions[$dateKey]->sortByDesc('logout_at')->first();

                $attendanceData[$dateKey] = [
                    'status' => 'Present',
                    'checkin' => $firstSession->login_at ? Carbon::parse($firstSession->login_at)->format('H:i') : '--',
                    'checkout' => $lastSession->logout_at ? Carbon::parse($lastSession->logout_at)->format('H:i') : '--'
                ];
            } elseif ($dateKey > $today) {
                $attendanceData[$dateKey] = [
                    'status' => 'N.A.',
                    'checkin' => '--',
                    'checkout' => '--'
                ];
            } else {
                $attendanceData[$dateKey] = [
                    'status' => 'Absent',
                    'checkin' => '--',
                    'checkout' => '--'
                ];
            }

            $currentDate->addDay();
        }

        return view('admin.hr.attendance.index', compact(
            'attendanceData',
            'startDate',
            'endDate',
            'month',
            'users',
            'selectedUserId'
        ));
    }
}
