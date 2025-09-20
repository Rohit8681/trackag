@extends('admin.layout.layout')

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
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
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
                                        $currentDate = $startDate->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                        $endCalendarDate = $endDate->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                                    @endphp

                                    @while ($currentDate <= $endCalendarDate)
                                        <tr>
                                            @for ($i = 0; $i < 7; $i++)
                                                <td style="vertical-align: top; padding: 5px; min-height: 100px;">
                                                    <strong>{{ $currentDate->format('j') }}</strong>
                                                    <br>
                                                    @php
                                                        $dateKey = $currentDate->format('Y-m-d');
                                                        $attendance = $attendanceData[$dateKey] ?? ['status' => 'N.A.', 'checkin' => '--', 'checkout' => '--'];
                                                    @endphp

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
                                                @php $currentDate->addDay(); @endphp
                                            @endfor
                                        </tr>
                                    @endwhile
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
