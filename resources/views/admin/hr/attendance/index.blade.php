@extends('admin.layout.layout')
@section('title', 'List Attendance | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">Attendance Calendar</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <form method="GET" action="{{ route('attendance.index') }}" class="d-inline-flex align-items-center">
                        <select name="user_id" class="form-select me-2" style="max-width: 220px;">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $selectedUserId ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="month" name="month" value="{{ $month }}" class="form-control me-2" style="max-width: 160px;">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th>Sunday</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Setup month range
                                    $firstDay = $startDate->copy()->startOfMonth();
                                    $lastDay = $endDate->copy()->endOfMonth();

                                    // What day of week the month starts on (0 = Sunday, 1 = Monday, ... 6 = Saturday)
                                    $startDayOfWeek = $firstDay->dayOfWeek;

                                    // Count total days in month
                                    $totalDays = $firstDay->daysInMonth;

                                    $currentDay = 1;
                                @endphp

                                <tr>
                                    {{-- Blank cells before first date --}}
                                    @for ($i = 0; $i < $startDayOfWeek; $i++)
                                        <td style="background-color: #f8f9fa;"></td>
                                    @endfor

                                    {{-- Loop through all days of the month --}}
                                    @while ($currentDay <= $totalDays)
                                        @php
                                            $currentDate = $firstDay->copy()->day($currentDay);
                                            $dateKey = $currentDate->format('Y-m-d');
                                            $attendance = $attendanceData[$dateKey] ?? [
                                                'status' => 'N.A.',
                                                'checkin' => '--',
                                                'checkout' => '--'
                                            ];
                                        @endphp

                                        <td style="vertical-align: top; padding: 8px; min-height: 100px;">
                                            <strong>{{ $currentDay }}</strong><br>

                                            @if ($attendance['status'] === 'Present')
                                                <div class="badge bg-success">Present</div>
                                                <div class="mt-1">IN: <strong>{{ $attendance['checkin'] }}</strong></div>
                                                <div class="mt-1">OUT: <strong>{{ $attendance['checkout'] }}</strong></div>
                                            @elseif ($attendance['status'] === 'Absent')
                                                <div class="badge bg-danger">Absent</div>
                                                <div class="mt-1">IN: --</div>
                                                <div class="mt-1">OUT: --</div>
                                            @else
                                                <div class="badge bg-secondary">N.A.</div>
                                            @endif
                                        </td>

                                        @if (($startDayOfWeek + $currentDay) % 7 == 0)
                                            </tr><tr>
                                        @endif

                                        @php $currentDay++; @endphp
                                    @endwhile

                                    {{-- Fill remaining cells to complete the week --}}
                                    @php
                                        $remaining = (7 - (($startDayOfWeek + $totalDays) % 7)) % 7;
                                    @endphp
                                    @for ($i = 0; $i < $remaining; $i++)
                                        <td style="background-color: #f8f9fa;"></td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
