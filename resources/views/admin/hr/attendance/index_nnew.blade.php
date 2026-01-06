@extends('admin.layout.layout')
@section('title', 'Attendance Status | Trackag')

@push('styles')
<style>
    .attendance-table th,
    .attendance-table td {
        font-size: 13px;
        padding: 4px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .user-name {
        text-align: left !important;
        font-weight: 600;
        min-width: 220px;
        background: #fff;
        position: sticky;
        left: 0;
        z-index: 2;
    }

    thead th:first-child {
        position: sticky;
        left: 0;
        z-index: 3;
        background: #f8f9fa;
    }

    select.attendance-select {
        border: none;
        font-weight: 600;
        font-size: 13px;
        padding: 2px 6px;
        width: 55px;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
    }

    /* .status-P  { background: #e8f5e9; color: #1b5e20; }
    .status-A  { background: #fdecea; color: #b71c1c; }
    .status-PN { background: #fff8e1; color: #e65100; }
    .status-NA { background: #f1f3f5; color: #6c757d; }
    .status-WO { background: #e9ecef; color: #495057; font-weight: 600; }
    .status-H {
    background: #e3f2fd;
    color: #0d47a1;
    font-weight: 600;
} */
 .status-P_FULL { background:#e8f5e9; color:#1b5e20; }
.status-P_HALF { background:#fff3cd; color:#856404; }
.status-A { background:#fdecea; color:#b71c1c; }
.status-NA { background:#f1f3f5; color:#6c757d; }
.status-WO { background:#e9ecef; color:#495057; font-weight:600; }
.status-H { background:#e3f2fd; color:#0d47a1; font-weight:600; }
</style>
@endpush

@section('content')
<main class="app-main">
    <div class="app-content">
        <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">Attendance Calendar</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <form method="GET" action="{{ route('attendance.index') }}" class="d-inline-flex align-items-center">
                        {{-- <select name="user_id" class="form-select me-2" style="max-width: 220px;">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $selectedUserId ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select> --}}
                        <input type="month" name="month" value="{{ $month }}" class="form-control me-2" style="max-width: 160px;">
                        <button type="submit" class="btn btn-primary">Go</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="container-fluid">

            <div class="card card-outline card-primary">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered attendance-table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    @php $date = $startDate->copy(); @endphp
                                    @while ($date <= $endDate)
                                        <th>
                                            {{ $date->format('D') }} <br>
                                            <small>{{ $date->format('d-m') }}</small>
                                        </th>
                                        @php $date->addDay(); @endphp
                                    @endwhile
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="user-name">{{ $user->name }}</td>

                                        @php $date = $startDate->copy(); @endphp
                                        @while ($date <= $endDate)
                                            @php
                                                $dateKey = $date->format('Y-m-d');
                                                $status = $attendance[$user->id][$dateKey] ?? 'NA';
                                            @endphp

                                            <td>
                                            @if(in_array($status,['WO','H']))
                                                <select class="attendance-select status-{{ $status }}" disabled>
                                                    <option>{{ $status }}</option>
                                                </select>
                                            @else
                                                <select
                                                    class="attendance-select status-{{ $status }}"
                                                    data-user="{{ $user->id }}"
                                                    data-date="{{ $dateKey }}"
                                                >
                                                    <option value="P_FULL" {{ $status=='P_FULL' ? 'selected' : '' }}>P (Full)</option>
                                                    <option value="P_HALF" {{ $status=='P_HALF' ? 'selected' : '' }}>P (Half)</option>
                                                    <option value="A" {{ $status=='A' ? 'selected' : '' }}>A</option>

                                                    @foreach($leaves as $leave)
                                                        <option value="{{ $leave->code }}"
                                                            {{ $status==$leave->code ? 'selected' : '' }}>
                                                            {{ $leave->leave_name }}
                                                        </option>
                                                    @endforeach

                                                    <option value="NA" {{ $status=='NA' ? 'selected' : '' }}>NA</option>
                                                </select>
                                            @endif
                                            </td>

                                            @php $date->addDay(); @endphp
                                        @endwhile
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.attendance-select').forEach(el => {
    el.addEventListener('change', function () {

        let status = this.value;
        let userId = this.dataset.user;
        let date   = this.dataset.date;

        this.className = 'attendance-select status-' + status;

        fetch("{{ route('attendance.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                user_id: userId,
                date: date,
                status: status
            })
        });
    });
});
</script>
@endpush

